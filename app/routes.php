<?php 
return [

  '/'=> [
    'controller' => 'BaseController@home',
    'name' => 'homepage',
    'area' => 'site'
  ],
  '/login'=> [
    'controller' => 'BaseController@login',
    'name' => 'login',
    'area' => 'site'
  ],
  '/register'=> [
    'controller' => 'BaseController@register',
    'name' => 'register',
    'area' => 'site'
  ],
  '/logout'=> [
    'controller' => 'BaseController@logout',
    'name' => 'logout',
    'area' => 'site'
  ],
  '/user'=> [
    'controller' => 'UserController@home',
    'name' => 'userAccaunt',
    'area' => 'site'
  ],
  '/ajax'=> [
    'controller' => 'AjaxController@start',
    'name' => 'ajax',
    'area' => 'site'
  ],
  '/404'=> [
    'controller' => 'BaseController@Page404',
    'name' => '404',
    'area' => 'site'
  ],


];
