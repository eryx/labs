<?php
defined('SYS_ENTRY') or die('Access Denied!');


final class Cm_Model_Datax
{
    public static function getFeed($query)
    {
        $limit = 10;
        $where = array();
        $order = array();
            
        if (isset($query->limit)) {
            $limit = $query->limit;
        }            
        if (isset($query->order)) {
            $order = explode(",", $query->order);
        }
        
        $_datax = new Common_Data($query->type);
        
        $items  = $_datax->getFeed($where, $order, $limit);
    
        return $items;
    }
    
    public static function getBlock($file, $_items)
    {
        if (is_file($file)) {
            ob_start();
            include $file;
            $str = ob_get_contents();
            ob_end_clean();
            return $str;
        }
    
        return false;
    }
}
