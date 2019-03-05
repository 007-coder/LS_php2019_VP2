<?php 
class AjaxController extends BaseController
{

  public function __construct($controller)
  {
    parent::__construct($controller);
  }

  protected function start()
  {
    wrap_pre(__METHOD__);    
    $data = [];
    $this->render('', $data, true);
  }
  

}