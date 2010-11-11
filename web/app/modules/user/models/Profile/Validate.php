<?php

defined('SYS_ENTRY') or die('Access Denied!');


require_once 'Zend/Validate.php';

class User_Model_Profile_Validate
{
    public static function isValid(&$params, &$message = null) 
    {
		
		if (!Zend_Validate::is($params['birthday'], 'Date')) {
            $message = 'This is not a valid date';
            return false;
        }

		if (!Zend_Validate::is($params['name'], 'NotEmpty')) {
            $message = 'name can not be empty';
            return false;
        }

        return true;
    }
 
}
