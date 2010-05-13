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
 * @category   Common
 * @package    Common_Db
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Db.php 838 2010-03-28 05:30:40Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Common_Db
 *
 * @category   Common
 * @package    Common_Db
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Common_Db
{
    protected static $_insts;
    protected static $_instances = array();
    
    public static function get($class) 
    {
        if (!isset(self::$_insts[$class]) || self::$_insts[$class] === NULL) {
            self::$_insts[$class] = new $class();
        }
        return self::$_insts[$class];
    }
    
    public static function getInstance($params)
    {
    	if (!isset($params['name']) || !isset($params['primary'])) {
    		return false;
    	}
    	
        if (!isset(self::$_instances[$params['name']]) || self::$_instances[$params['name']] === NULL) {
            self::$_instances[$params['name']] = new Common_Db_Table($params);
        }
        
        return self::$_instances[$params['name']];
    }
}
