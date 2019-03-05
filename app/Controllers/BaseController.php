<?php 
class BaseController extends Application
{
  protected $currController;

  public function __construct($controller = '')
  {
    parent::__construct();
    $this->currController = $this;    
  }

  protected function logout()
  {
    wrap_pre(__METHOD__);
  }

  protected function register()
  {
    if (isset($_POST) && count($_POST)) {

    } else {
      if ($this->user['id']) {
        $this->redirect('', 'name:userAccaunt');
      } else {
        $this->redirect('', 'name:login');
      }
    }    
  }

  protected function login()
  { 
    if (isset($_POST) && count($_POST) && !$this->user['id']) {

    } else if (!isset($_POST) && $this->user['id']) {
      $this->redirect('', 'name:userAccaunt');
    } else if (!$this->user['id']) {
      $this->render('login');
    }
  }

  protected function home()
  {
    $this->render('homepage');
  }

  protected function Page404()
  {
    $this->render('404');
  }

  


  

}