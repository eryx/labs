<?php
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * @see Common_Data_Db
 */
require_once 'Common/Data/Db.php';

class Common_Data_Field_Config extends Common_Db_Table
{
    public function init()
    {
        $this->setOptions(Common_Data_Db::$field_config);
    }
}
