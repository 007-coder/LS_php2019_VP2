<head>
  <meta charset="<?php echo $tmplData['htmlCharset']  ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?php echo $tmplData['meta']['description']  ?>">
  <meta name="author" content="<?php echo $tmplData['meta']['author']  ?>"> 
  <meta name="keywords" content="<?php echo $tmplData['meta']['keywords']  ?>">   
  <title><?php echo $tmplData['meta']['title']  ?></title>

  <?php 
  // Подключаем CSS
  if (count($tmplData['styles'])) {
    foreach ($tmplData['styles'] as $type => $styles) {
      if ($type == 'relative' && count($styles)) {
        foreach ($styles as $style) {
          echo '<link href="'.$tmplData['assetsPathes']['css'] . $style . '" rel="stylesheet">';
        }        
      } else if ($type == 'absolute' && count($styles)) {
        foreach ($styles as $style) {
          echo '<link href="' . $style . '" rel="stylesheet">';
        }
      }
    }
  } 

  if (count($tmplData['stylesInline'])) {
    $inlineStylesStr = '';
    foreach ($tmplData['stylesInline'] as $styleInline) {
      $inlineStylesStr .= $styleInline;      
    }
    echo '<style>'.$inlineStylesStr.'</style>';
  } ?> 

</head>