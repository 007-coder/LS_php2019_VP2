<?php 
class BaseModel extends Application
{
 
  public function __construct($model = '')
  {
    parent::__construct();
  }

  public function isUserExists($email, $password) 
  {
    $result = false;
    $query = "SELECT * FROM `users` WHERE email='".$email."' ";

    $stmt = $this->db->prepare($query);
    $stmt->execute();
    $user = $stmt->fetch(\PDO::FETCH_ASSOC); 
    
    if (count($user) && $user['publish']) {
     /* echo $password.'</br>';
      echo buildPasswordHash($password).'</br>';

      wrap_pre(password_verify($password, $user['password']), 'password_verify');*/

      if (password_verify($password, $user['password'])) {
        $result = $user;
      }
    }

    return $result;    

  }
}