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
    if ($this->user['id']) {
      $this->redirect('name:userAccaunt');
    } else {
      if (isset($_POST) && count($_POST)) {

        wrap_pre($_POST, '$_POST');
        $data['meta']['title'] = $this->l10n->_t('PAGE_TITLE_LOGIN_REGISTER');
        $this->render('login',$data);

      } else {
        $data['meta']['title'] = $this->l10n->_t('PAGE_TITLE_LOGIN_REGISTER');
        $this->render('login',$data);
      }
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