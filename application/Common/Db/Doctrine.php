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
 * @version    $Id: Session.php 811 2010-02-28 12:08:39Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

require_once 'Doctrine.php';

/**
 * Class Common_Db_Doctrine
 *
 * @category   Common
 * @package    Common_Db
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Common_Db_Doctrine
{
    public static function getManager()
    {
        spl_autoload_register(array('Doctrine', 'autoload'));

        return Doctrine_Manager::getInstance();
    }
    
    public static function getConnection($dsn)
    {
        spl_autoload_register(array('Doctrine', 'autoload'));

        $manager = Doctrine_Manager::getInstance();

        return Doctrine_Manager::connection($dsn);
    }
}
