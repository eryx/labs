<?php
/**
 * SmartKit
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   Bootstrap
 * @package    Bootstrap
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Bootstrap.php 856 2010-05-07 16:05:39Z evorui $
 */

/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Bootstrap
 *
 * @category   Bootstrap
 * @package    Bootstrap
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialize common configuration  
     *
     * @return void
     */
    protected function _initSystem()
    {
        /** 
         * Getting database configuration
         */
        $conf = new Zend_Config_Ini('configs/common.ini');

        /** 
         * Loading the default Zend_Db_Adapter_* configs
         *      and create an default instance ($db) of database
         */
        if (isset($conf->database->master)) {
        
            $db = Zend_Db::factory($conf->database->master);
        
            Zend_Db_Table_Abstract::setDefaultAdapter($db);

            Zend_Registry::set('db', $db);
        }
    }

    /**
     * Un-quotes all quoted strings. 
     *
     * @return void
     */
    protected function _initStringQuoted()
    {
        // emulate magic_quotes_gpc off 
        if (get_magic_quotes_gpc()) {
            $_REQUEST   = $this->_stripslashesArray($_REQUEST);
            $_GET       = $this->_stripslashesArray($_GET);
            $_POST      = $this->_stripslashesArray($_POST);
            $_COOKIE    = $this->_stripslashesArray($_COOKIE);
        }
    }
    
    /**
     * Escapes a value for input 
     *
     * @return void
     */
    protected function _initEscape()
    {
        $_REQUEST   = $this->_escapeArray($_REQUEST);
        $_GET       = $this->_escapeArray($_GET);
        $_POST      = $this->_escapeArray($_POST);
        $_COOKIE    = $this->_escapeArray($_COOKIE);
    }
    
    /**
     * Do stripslashes for array
     *
     * @param array $array
     * @return mixed
     */
    private function _stripslashesArray(&$array)
    {
        foreach ($array as $key => $var) {
            if ($key != 'argc' && $key != 'argv' 
                && (strtoupper($key) != $key || ''.intval($key) == "$key")) {
                if (is_string($var)) {
                    $array[$key] = stripslashes($var);
                } elseif (is_array($var)) {
                    $array[$key] = $this->_stripslashesArray($var);
                }
            }
        }
    
        return $array;
    }
    
    /**
     * Do escape for array
     *
     * @param array $array
     * @return mixed The escaped value.
     */
    private function _escapeArray(&$array)
    {
        foreach ($array as $key => $var) {
            if (is_string($var)) {
                //$array[$key] = htmlspecialchars($var);
            } elseif (is_array($var)) {
                $array[$key] = $this->_escapeArray($var);
            }
        }
    
        return $array;
    }
}
