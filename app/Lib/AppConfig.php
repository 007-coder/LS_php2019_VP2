<?php 
class AppConfig {

  private $config =  
  [
    'templates' => [
      'admin' => 'default',
      'site' => 'default'
    ],
    'database' => [
      'host'=>'localhost',
      'dbname'=>'ls2019_vp2',
      'user'=>'root',
      'password'=>'vertrigo',
      'charset' => 'UTF-8'        
    ]
      
  ];
 

  public function getConfig($configArea = '')
  {
    if (empty($configArea)) {
      return $this->config;
    } else {
      return $this->config[$configArea];      
    }
  }

}