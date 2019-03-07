<?php 
$layoutsDir = $tmplData['templateDir'] .DS .'layouts';
$l10n = $tmplData['l10n'];
?>
<!doctype html>
<html lang="<?php echo $tmplData['htmlLang'] ?>">
  
  <?php require_once ($layoutsDir . DS . 'head.php'); ?>

  <body>

    <?php
    require_once ($layoutsDir . DS . 'header.php');

    if (!empty($tmplData['message']['text'])) {
      require_once ($layoutsDir . DS . 'message.php');
    }

    if (empty($tmplData['page']) || $tmplData['page'] == 'homepage' ) {
      require_once ($layoutsDir . DS . 'homepage.php');
    } else {
      require_once ($layoutsDir . DS . $tmplData['page'].'.php');
    } 

    require_once ($layoutsDir . DS . 'footer.php'); 

    require_once ($layoutsDir . DS . 'bottomScripts.php'); ?>
    
    </body>
   
</html>
