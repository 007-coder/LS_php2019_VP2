<?php
function wrap_pre($data, $title = '') {
  $count = ((is_array($data) || is_object($data)) && count($data)) ? ' ('.count($data).') ' : '';
  echo '<pre><h4><b>'.$title.$count.'</b></h4>'.print_r($data, true).'</pre>';
}

function get_actual_url() {
  $actual_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

  return $actual_url;
}

function getLangCodeISO($lang) {
  $explLang = explode('_', $lang);
  return $explLang[0];
}

function buildPasswordHash($password) {
  $options = ['cost' => 11];     
  return password_hash($password, PASSWORD_BCRYPT, $options);
}
