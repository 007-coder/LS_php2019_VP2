<?php 
class BaseModel extends Application
{
  protected $currModel;

  public function __construct($model = '')
  {
    parent::__construct();
    $this->currModel = $this;
  }
}