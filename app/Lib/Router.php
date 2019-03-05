<?php 
class Router {

  protected $routes;

  public function __construct($routesFile = '')
  {
    if (!empty($routesFile) && file_exists($routesFile)) {
      $this->routes = require_once($routesFile);
    } else {
      $this->routes = require_once(APP_DIR . DS . 'routes.php');
    }
  }


  public function start($route = '')
  {
    $result = [
      'controller'=>null,
      'method'=>null,
      'name'=>null,
      'route' => null
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
    } else {
      if ($reqURI == '/index.php') {
        $result['controller'] = 'BaseController';
        $result['method'] = 'home';
        $result['name'] = 'homepage';
        $result['route'] = '/index.php';
      } else {
        $result['controller'] = 'BaseController';
        $result['method'] = 'Page404';
        $result['name'] = '404';
        $result['route'] = '/404';  
      }
      
    }    

    return $result;
  }

  public function getNamedRoute($routeName)
  { 
    $result = [
      'controller'=>null,
      'method'=>null,
      'name'=>null,
      'route' => null
    ];
    
    $routeFound = '';   
    foreach ($this->routes as $route => $routeData) {
      if (isset($routeData['name']) && $routeData['name'] == $routeName) {
        $routeFound = $route;
      } 
    }

    return (empty($routeFound)) ? $result : $this->start($routeFound);
  }

}