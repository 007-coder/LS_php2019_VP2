<?php
if (count($tmplData['scripts'])) {
  foreach ($tmplData['scripts'] as $type => $scripts) {
      if ($type == 'relative' && count($scripts)) {
        foreach ($scripts as $script) {
          echo '<script type="text/javascript" src="' . $tmplData['assetsPathes']['js'] . $script . '"></script>';
        }        
      } else if ($type == 'absolute' && count($scripts)) {
        foreach ($scripts as $script) {
          echo '<script type="text/javascript" src="'. $script .'"></script>';
        }
      }
    }
}

?>