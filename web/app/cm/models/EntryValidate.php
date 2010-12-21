<?php
defined('SYS_ROOT') or die('Access Denied!');


class Cm_Model_EntryValidate
{
    public function isValid(&$params, &$message)
    {
        $params['title'] = isset($params['title']) ? trim($params['title']) : null;
        if (is_null($params['title']) || empty($params['title'])) {
            $message = sprintf("%s can't be null", 'Title');
            return false;
        }
        
		if (!isset($params['content']) || is_null($params['content']) || empty($params['content'])) {
		    $message = sprintf("%s can't be null", 'Content');
            return false;
		}
		
		if (!isset($params['cat']) || (int)$params['cat'] == 0) {
		    $message = sprintf("%s can't be null", 'Category');
            return false;
		}
		
		if (!isset($params['allow_comment']) 
		    || ((int)$params['allow_comment'] != 0 && (int)$params['allow_comment'] != 1)) {
		    $params['allow_comment'] = 0;
		}
    
        return true;
    }
}
