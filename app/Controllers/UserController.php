<?php 
class UserController extends BaseController
{

  public function __construct($controller)
  {
    parent::__construct($controller);
  }

  protected function home()
  {
    wrap_pre(__METHOD__);
  }

  

}