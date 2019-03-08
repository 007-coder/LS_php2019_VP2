<?php 
class BaseController extends Application
{
 
  public function __construct($controller = '')
  {
    parent::__construct();
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
    //IF USER ALREADY AUTHORIZED
    if ($this->user['id']) {
      $this->redirect('name@userAccaunt');
    }
    // IF A GUEST
    else {

      if (isset($_POST) && count($_POST)) {        
        $vParams = [
          'validationRules' => [
            'email' => 'required|valid_email|min_len,4|max_len,40',
            'password' => 'required|min_len,6',
            'formName' => 'required|exact_len,5|contains,login',
          ],
          'filterRules' => [
            'email' => 'sanitize_string|sanitize_email|trim'
          ],
        ]; 
        $filterPostKeys = ['email', 'password', 'formName'];
        $validatedData = $this->validate($_POST, $vParams, $filterPostKeys);
       

        //Если данные валидны
        if ($validatedData['allValid']) {  

          $isUserExists = $this->getModel()->isUserExists($validatedData['validatedData']['email'], $validatedData['validatedData']['password']);

          //USER EXISTS
          if (is_array($isUserExists) && count($isUserExists)) {
            // успешная авторизация. устанавливаем пользователя.
            $this->setUser($isUserExists);      
            $this->redirect('name:userAccaunt');    

          } 
          // USER NOT EXIXTS
          else {
            echo 'USER_NOT_EXISTS.</br>';
            $data['message'] = [
              'type' => 'error',        
              'text' => $data['meta']['title'] = $this->l10n->_t('USER_NOT_EXISTS')
            ];
          }
        }

        //если данные не валидны
        else {
          foreach ($validatedData['errors']['values'] as $key => $message) {
            $data['formValidationResults']['errors'][$key] = $message;
          }
          foreach ($validatedData['errors']['valid'] as $key => $validData) {
            $data['formValidationResults']['valid'][$key] = $validData;
          }

          $data['message'] = [
            'type' => 'error',        
            'text' => $data['meta']['title'] = $this->l10n->_t('FORM_WRONG_SUBMITED_DATA')
          ];
          

        }        

      } 
      

      $data['meta']['title'] = $this->l10n->_t('PAGE_TITLE_LOGIN_REGISTER');
      $this->render('login',$data);

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