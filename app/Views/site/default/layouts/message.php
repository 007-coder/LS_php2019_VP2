<?php  ?>
<div class="row justify-content-center">
  <div class="col-sm-8 col-md-6">
    <div class="alert <?php echo ($tmplData['message']['type'] == 'ok') ? 'alert-success' : 'alert-danger' ?> mt-4 mb-4" role="alert">
      <p class="text-center"><?php echo $tmplData['message']['text'] ?></p>      
    </div>    
  </div>
</div>
