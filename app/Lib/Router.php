<?php 
class Router {

  protected $routes;
  protected $currentRoute = [];

  public function __construct($routesFile = '')
  {
    if (!empty($routesFile) && file_exists($routesFile)) {
      $this->routes = require_once($routesFile);
    } else {
      $this->routes = require_once(APP_DIR . DS . 'routes.php');
    }
    $this->currentRoute = [];
  }


  public function start($route = '')
  {
    $result = [
      'controller'=>null,
      'method'=>null,
      'name'=>null,
      'route' => null,
      'area' => null
    ];

    if (empty($route)) {
      $reqURI = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];  
    } else {
      $reqURI = $route;
    }      
    
    if (array_key_exists($reqURI, $this->routes)){
      $routeData = $this->routes[$reqURI];
      $routeDataController = explode('@', $routeData['controller']);
      $result['controller'] = $routeDataController[0];
      $result['method'] = $routeDataController[1];
      $result['name'] = isset($routeData['name']) ? $routeData['name'] : '';
      $result['route'] = $reqURI;
      $result['area'] = isset($routeData['area']) ? $routeData['area'] : 'site';
    } else {
      if ($reqURI == '/index.php') {
        $result['controller'] = 'BaseController';
        $result['method'] = 'home';
        $result['name'] = 'homepage';
        $result['route'] = '/index.php';
        $result['area'] = 'site';
      } else {
        $result['controller'] = 'BaseController';
        $result['method'] = 'Page404';
        $result['name'] = '404';
        $result['route'] = '/404';  
        $result['area'] = 'site';
      }
      
    }  

    $this->currentRoute = $result;

    return $this->currentRoute;
  }

  public function getNamedRoute($routeName)
  { 
    $result = [
      'controller'=>null,
      'method'=>null,
      'name'=>null,
      'route' => null,
      'area' => null
    ];
    
    $routeFound = '';   
    foreach ($this->routes as $route => $routeData) {
      if (isset($routeData['name']) && $routeData['name'] == $routeName) {
        $routeFound = $route;
      } 
    }
    if (empty($routeFound)){
      $this->currentRoute = $result;
      return $this->currentRoute;
    } else {
      return $this->start($routeFound);
    }
        
  }

  public function getRouteArea(){
    return $this->currentRoute['area'];
  }

}