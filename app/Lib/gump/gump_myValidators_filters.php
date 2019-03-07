<?php
require("gump.class.php");

class MyCustomGUMP extends GUMP {
	
	public function __construct($lang = 'en')
    {
       parent::__construct($lang);		
    }

	
	/**
     * Determine if the provided value contains regular text, with [ .,!?-_0-9A-Za-z].
     *
     * Usage: '<index>' => 'some_text'
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    public function validate_some_text($field, $input, $param = null)
    {
        if (!isset($input[$field]) || empty($input[$field])) {
            return;
        }

        if (!preg_match("/^([\w,\.!\?-_\sÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i", $input[$field]) !== false) {
            return array(
                'field' => $field,
                'value' => $input[$field],
                'rule' => __FUNCTION__,
                'param' => $param,
            );
        }
    }
    
    
	
}
?>