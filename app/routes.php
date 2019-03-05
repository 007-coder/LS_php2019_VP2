<?php 
return [

  '/'=> [
    'controller' => 'BaseController@home',
    'name' => 'homepage'
  ],
  '/login'=> [
    'controller' => 'BaseController@login',
    'name' => 'login'
  ],
  '/register'=> [
    'controller' => 'BaseController@register',
    'name' => 'register'
  ],
  '/logout'=> [
    'controller' => 'BaseController@logout',
    'name' => 'logout'
  ],
  '/user'=> [
    'controller' => 'UserController@home',
    'name' => 'userAccaunt'
  ],
  '/ajax'=> [
    'controller' => 'AjaxController@start',
    'name' => 'ajax'
  ],
  '/404'=> [
    'controller' => 'BaseController@Page404',
    'name' => '404'
  ],


];
