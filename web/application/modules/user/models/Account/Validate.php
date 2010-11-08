<?php

defined('SYS_ENTRY') or die('Access Denied!');


require_once 'Zend/Validate.php';

class User_Model_Account_Validate
{
    public static function isPasswordChange(&$params, &$message = null) 
    {
        if (!isset($params['pass_current'])) {
		    $message = 'pass_current can not be null';
            return false;
		}
        if (!isset($params['pass']) || !isset($params['pass_confirm'])) {
            $message = 'pass or pass_confirm can not be empty';
            return false;
        }
		if (!Zend_Validate::is($params['pass'], 'StringLength', array(6, 32))) {
		    $message = 'pass must between 6 and 32 characters long';
            return false;
        }
		if ($params['pass'] != $params['pass_confirm']) {
		    $message = 'Passwords do not match';
            return false;
		}

        return true;
    } 
}
