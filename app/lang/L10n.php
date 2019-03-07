<?php 
if (!defined('DS')) { define('DS',DIRECTORY_SEPARATOR); }

class L10n
{
  protected $l10n;
  protected $lang;
  protected $area;
  protected $template;

  public function __construct($lang, $area, $template)
  {
    $this->lang = $lang;
    $this->area = $area;
    $this->template = $template;
    require (__DIR__ . DS . $area . DS . $template . DS . $lang . '.php' );
  }

  public function _t($langString)
  {
    return $this->l10n[$langString];
  }

  public function getAllStrings(){
    return $this->l10n;
  }

}