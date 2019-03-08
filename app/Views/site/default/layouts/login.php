<?php 
/*if (isset($tmplData['formValidationResults'])) {
  wrap_pre($tmplData['formValidationResults'], '$tmplData[\'formValidationResults\']');
  
}*/ ?>

<div class="page-wrap login">
  <div class="container login-container">
    <div class="row">
      <div class="col-md-6 login-form-1">
        <h3><?php echo $l10n['PAGE_LOGREGISTER_LOGIN'] ?></h3>
        <form action="<?php echo BASE_URI.'/index.php/login'?>" method="post">
          <div class="form-group">
              <input type="text" name="email" class="form-control" placeholder="Your Email *" value="" required />
          </div>
          <div class="form-group">
              <input type="password" name="password" class="form-control" placeholder="Your Password *" value="" required />
          </div>
          <div class="form-group">
              <input type="submit" class="btnSubmit" value="Login" />
          </div>
          <input type="hidden" name="formName" value="login">           
        </form>
      </div>
      <div class="col-md-6 login-form-2">
        <h3><?php echo $l10n['PAGE_LOGREGISTER_REGISTER'] ?></h3>
        <form action="<?php echo BASE_URI.'/index.php/register'?>" method="post">
          <div class="form-group">
              <input type="text" name="email" class="form-control" placeholder="Your Email *" value="" required />
          </div>
          <div class="form-group">
              <input type="password" name="password" class="form-control" placeholder="Your Password *" value="" required />
          </div>
          <div class="form-group">
              <input type="text" name="age" class="form-control" placeholder="Your Age *" value="" required />
          </div>
          <div class="form-group">
              <input type="submit" class="btnSubmit" value="Register" />
          </div>  
          <input type="hidden" name="formName" value="register">    
        </form>
      </div>
    </div>
  </div>
</div>