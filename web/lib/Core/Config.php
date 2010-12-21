<?php

defined('SYS_ROOT') or die('Access Denied!');

/**
 * Static api to get configuration in singleton pattern
 * 
 * @category   Core
 * @package    Core_Config
 */
final class Core_Config
{
    /**
     * @var array
     */
    private static $_configs = array();

    /**
     * Get configuration by config-file-name, Ignore extension!
     *
     * @param $name string 
     * @retrun Zend_Config
     */
    public static function get($name)
    {
        if (isset(self::$_configs[$name])) {
            return self::$_configs[$name];
        }

        if (file_exists(SYS_ROOT."/conf/{$name}.php")) {
            self::$_configs[$name] = require SYS_ROOT."/conf/{$name}.php";
            return self::$_configs[$name];
        }
        
        return false;
    }
}
