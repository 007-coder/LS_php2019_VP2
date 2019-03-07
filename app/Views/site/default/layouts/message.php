<?php  ?>
<div class="alert <?php echo ($tmplData['message']['type'] == 'ok') ? 'alert-success' : 'alert-danger' ?> mt-4 mb-4" role="alert">
  <?php echo $tmplData['message']['text'] ?>
</div>