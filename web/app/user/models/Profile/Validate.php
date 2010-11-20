<?php

defined('SYS_ROOT') or die('Access Denied!');


class User_Model_Profile_Validate
{
    public static function isValid(&$params, &$message = null) 
    {
		if (!Zend_Validate::is($params['name'], 'NotEmpty')) {
            $message = 'name can not be empty';
            return false;
        }
        
        if (!Zend_Validate::is($params['birthday'], 'Date')) {
            $message = 'This is not a valid date';
            return false;
        }

        return true;
    }
}
