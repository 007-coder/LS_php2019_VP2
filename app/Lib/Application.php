<?php 
class Application 
{ 
  protected       $config; 
  protected       $db;
  protected       $router;
  protected       $viewData= [];
  protected       $i10n;
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
    $this->db();
  }

  public function runApp() 
  {   
    $routeData = $this->router->start();
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

  protected function redirect($where, $code = '')
  {
    // url@ route@ name@
    header('Location: ' . $url);
    exit;
  }

  protected function setViewData($data)
  {

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
        'title' => '',
        'description' => '',
        'keywords' => '',
        'author' => 'Vakulenko Yura | vakulenkoyura211@gmail.com | https://t.me/yura_v_007',
      ];    
      $this->viewData['assetsPathes'] = [
        'css' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/css/',
        'js' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/js/',
        'img' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/img/',
        'fonts' => BASE_URI.'/assets/' . $area . '/' . $templateArea . '/fonts/',
      ];   
      $this->viewData['styles'] = [
        'relative' => [
          'bootstrap.min.css',
          'style.css'
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
          'bootstrap.bundle.min.js'
        ],
        'absolute' => [
          'https://code.jquery.com/jquery-3.3.1.slim.min.js'
        ]
      ];
      $this->viewData['scriptsInline'] = [];
      $this->viewData['message'] = [
        'type'=>'', // error | ok
        'text'=>''
      ];
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