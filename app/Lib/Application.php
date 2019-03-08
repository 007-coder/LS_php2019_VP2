<?php 
/*
  Created by Vakulenko Yuri at 31 years old age 
  Inspirited by my crazy mind while listening 
  Liszt - Après une lecture du Dante - Fantasia quasi sonata, S161/7
  https://www.youtube.com/watch?v=_PrsflaFB58
  https://www.youtube.com/watch?v=Q7JbSQvTqB8
*/

class Application 
{ 
  protected       $config; 
  protected       $db;
  protected       $router;
  protected       $validator;
  protected       $viewData= [];
  protected       $l10n;
  protected       $interfaceLang;
  protected       $availableLangs;
  protected       $area = 'site'; // site|admin (for Langs Loading)
  protected       $user = [
                    'id' => 0,
                    'data' => []
                  ];
  protected const TEMPLATE_DIR = APP_DIR . DS . 'Views';  


  public function __construct() 
  {
    $this->loader();
    $this->config = new AppConfig();    

    // путь к файлу с роутами. По умолчан APP_DIR.DS.'routes.php'
    $routerFile = ''; 
    $this->router = new Router($routerFile);

    $this->session();

    $this->interfaceLang = $_SESSION['interfaceLang'];
    $this->availableLangs = $_SESSION['availableLangs'];
    $this->loadInterfaceLang($this->interfaceLang);

    $this->db();
    $this->validator = new GUMP(getLangCodeISO($this->interfaceLang));
  }

  public function run() 
  {   
    $routeData = $this->router->start();
   
    $this->area = $this->router->getRouteArea();

    wrap_pre($this->user, __METHOD__.'$this->user'); 

    $method = $routeData['method'];    
    if ($routeData['controller'] == 'BaseController') {
      $this->getController()->$method();
    } else {
      $controller = str_replace('Controller', '', $routeData['controller']);     
      $this->getController($controller)->$method();
    }

  }

  protected function getConfig($area = '')
  {
    return $this->config->getConfig($area);
  }

  protected function getController($controller = '')
  {
    if (empty($controller)) {
      $_controller = new BaseController();
    } else {
      $controller = ucfirst($controller);
      require_once(CONTROLLERS_DIR.DS.$controller.'Controller.php');
      $controllerClass = $controller.'Controller';      
      $_controller = new $controllerClass($controller);
    }    

    return $_controller;
  }

  protected function getModel($model = '')
  {    
    if (empty($model)) {
      $_model = new BaseModel();
    } else {
      $model = ucfirst($model);
      require_once(MODELS_DIR.DS.$model.'Model.php');
      $modelClass = $model.'Model';      
      $_model = new $modelClass($model);
    }

    return $_model;
  }

  protected function loader() 
  {
    require_once(LIB_DIR.DS.'functions.php');
    require_once(LIB_DIR.DS.'AppConfig.php');
    require_once(LIB_DIR.DS.'Router.php'); 
    require_once(LIB_DIR.DS.'gump'.DS.'gump.class.php');  
    require_once(CONTROLLERS_DIR.DS.'BaseController.php'); 
    require_once(MODELS_DIR.DS.'BaseModel.php');    
  }

  protected function session() 
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    // SESSION User setup
    if (isset($_SESSION['user'])){
      if ($_SESSION['user']['id']) {
        $this->user['id'] = ($this->user['id']) ? $this->user['id'] : $_SESSION['user']['id'];

        if (empty($this->user['data'])) {
          $this->user['data'] = (count($_SESSION['user']['data'])) ? $_SESSION['user']['data'] : $this->getModel()->getUserData($this->user['id']);  
        }        
      }
    } else {
      $_SESSION['user'] = [
        'id' => 0,
        'data' => []
      ];      
    }

    // SESSION Language setup
    if (!isset($_SESSION['interfaceLang'])){
      $_SESSION['interfaceLang'] = $this->getDefaultLang($this->area); 
    }
     if (!isset($_SESSION['availableLangs'])){
      $_SESSION['availableLangs'] = $this->getAvailableLangs($this->area);
    }

  }

  protected function loadInterfaceLang($lang)
  { 
    require_once(LANG_DIR . DS . 'L10n.php');
    $templates = $this->getConfig('templates');
    $templateArea = (isset($templates[$this->area])) ? $templates[$this->area] : 'default'; 

    $this->l10n = new l10n($this->interfaceLang, $this->area, $templateArea);
  } 
  

  protected function db() 
  { 
    $dbConfig = $this->getConfig('database');
    try {
        $this->db = new PDO(
          'mysql:dbname=' . $dbConfig['dbname'] .
          ';host=' . $dbConfig['host'],
          $dbConfig['user'],
          $dbConfig['password']
        );        
    } catch (PDOException $e) {
        echo 'Подключение к БД не удалось: ' . $e->getMessage();
    } 
  }

  protected function redirect($where, $statusCode = '303')
  {
    
    /*header('Location: ' . $url, true, $statusCode);

    exit();*/
    // url@ route@ name@    
  }

  protected function getDefaultLang($area = 'site')
  { 
    $languages = $this->getConfig('languages');
    return $languages[$area]['default'];
  }

  protected function getAvailableLangs($area = 'site')
  { 
    $languages = $this->getConfig('languages');
    return $languages[$area]['availableLangs'];
  }  

  protected function validate($data, $params, $filterPostKeys = []) {
    $return  = [
      'allValid'=>false,
      'errors'=>[
        'values'=>[],
        'valid'=>[]
      ],
      'validatedData'=>[]
    ];
   
    if (count($filterPostKeys)) {
      $dataAfterFilter =[];
      foreach ($data as $kp => $postData) {
        if (in_array($kp, $filterPostKeys)) {
          $dataAfterFilter[$kp] = $postData;
        } 
      }  
    }

    $dataForValidation = (count($filterPostKeys)) ? $dataAfterFilter : $data;

    $this->validator->validation_rules($params['validationRules']); 
    $this->validator->filter_rules($params['filterRules']);
    $validatedData = $this->validator->run($dataForValidation);  

    // Валидация провалена
    if($validatedData === false)
    { 
      $return['errors']['values'] = $this->validator->get_errors_array();     
      $valid_data = [];

      foreach ($data as $kp => $_val)
      {       
        $return['errors']['valid'][$kp] = (!array_key_exists($kp, $return['errors']['values'])) ? $_val : '';         
      }
    } 
    //Валидация успешна
    else 
    {
        $return['allValid'] = true;
        $return['validatedData'] = $validatedData;
    }

    return $return;

  }

  protected function setUser($userData)
  {
    $_SESSION['user'] = [
      'id' => $userData['id'],
      'data' => [
        'id' => $userData['id'],
        'email' => $userData['email'],
        'publish' => $userData['publish'],
        'age' => $userData['age'],
        'name' => $userData['name'],
        'dateRegistered' => $userData['dateRegistered']
      ]
    ];

    $this->user = $_SESSION['user'];
  }

  protected function render($page, $data = [], $ajax = false, $area = 'site') 
  {
    $ajax = filter_var($ajax, FILTER_VALIDATE_BOOLEAN);
    $templateArea = '';    
    if (!in_array($area, ['site', 'admin'])) {
      throw RuntimeException(
        'Application::render() - unvalid Views area <b>'.$area.'
        </b>. Use only "site" or "admin" for $area method
        argument.'
      );
    }    

    // данные по умолчанию для шаблона 
      // ----------------------
    if ($ajax === false) {     
      $templates = $this->getConfig('templates');
      $templateArea = (isset($templates[$area])) ? $templates[$area] : 'default';

      $this->viewData['htmlLang'] = 'ru';
      $this->viewData['htmlCharset'] = 'utf-8';
      $this->viewData['meta'] = [
        'title' => $this->l10n->_t('PAGE_TITLE_HOMEPAGE'),
        'description' => '',
        'keywords' => '',
        'author' => 'Vakulenko Yura | vakulenkoyura211@gmail.com | https://t.me/yura_v_007',
      ];    
      // VIEW assets setup start 
      // ---------------------------
      $this->viewData['assetsPathes'] = [
        'css' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/css/',
        'js' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/js/',
        'img' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/img/',
        'fonts' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/fonts/',
      ];   
      $this->viewData['styles'] = [
        'relative' => [
          'bootstrap.min.css',
          'styles.css',
          'stylesOverride.css'
        ],
        'absolute' => []        
      ];
      $this->viewData['stylesInline'] = [
        '.bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }'
      ];
      $this->viewData['scripts'] = [
        'relative' => [
          'jquery-slim.min.js',
          'bootstrap.bundle.min.js'
        ],
        'absolute' => []
      ];
      $this->viewData['scriptsInline'] = [];      
      // ---------------------------

      $this->viewData['l10n'] = $this->l10n->getAllStrings();
      $this->viewData['router'] = $this->router;
      $this->viewData['message'] = [
        'type'=>'', // error | ok
        'text'=>''
      ];
      $this->viewData['formValidationResults'] = [];
      $this->viewData['page'] = ($page !='') ? $page : 'homepage';
      $this->viewData['templateDir'] = self::TEMPLATE_DIR . DS . $area . DS . $templateArea;
      // ----------------------

      if (count($data)) {
        $this->viewData = array_replace_recursive($this->viewData, $data);  
      }

      //wrap_pre($_SESSION, '$_SESSION');
      
    }

    $tmplData = ($ajax) ? json_encode($data) : $this->viewData;
    $path = ($ajax) ? self::TEMPLATE_DIR . DS . 'ajax.php' : self::TEMPLATE_DIR . DS . $area . DS . $templateArea . DS . 'start.php';
    if (!file_exists($path)) {
        throw RuntimeException(
          'Application::render() - file '.$path.' not exists.'
        );
    }
    //extract($data);
    include $path;
  }

}