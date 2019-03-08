<?php 
$direct_root_dir = false;
$root_dir = ($direct_root_dir) ? '/public' : '';

define('DS', DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', __DIR__);

define('BASE_URI', sprintf("%s://%s",isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'].$root_dir ));

define('APP_DIR', PUBLIC_DIR.DS.'..'.DS.'app');
define('CONTROLLERS_DIR', APP_DIR.DS.'Controllers');
define('MODELS_DIR', APP_DIR.DS.'Models');
define('LIB_DIR', APP_DIR.DS.'Lib');
define('LANG_DIR', APP_DIR.DS.'lang');

require(LIB_DIR.DS.'Application.php');

$app = new Application();
$app->run();

