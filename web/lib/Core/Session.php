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
 * @category   Core
 * @package    Core_Session
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Instance.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class Core_Session
 *
 * @category   Core
 * @package    Core_Session
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Core_Session
{
    protected static $_instance;
    
    public $sid = '';
    
    public $uid = 0;
    
    public $uname = 'guest';
    
    //public $roles = '0';
    
 
    // TODO
    protected function __construct()
    {
        if (isset($_SESSION['sid'])) {
            $sid = trim($_SESSION['sid']);
        } else if (isset($_COOKIE['sid'])) {
            $sid = trim($_COOKIE['sid']);
        } else {
            $sid = NULL;
        }
     
        if (!is_null($sid)) {

            try {
                $_session = Core_Dao::factory(array('name' => 'user_session'));
                $rs = $_session->getById($sid);
            } catch (Exception $e) {
                $rs = NULL;
            }

            if (isset($rs['id'])) {
                $this->sid   = $rs['id'];
                $this->uid   = $rs['uid'];
                $this->uname = $rs['uname'];
                //$this->roles    = $rs->roles;
            }
        }
    }

    // TODO
    public static function getInstance() 
    {
        if (self::$_instance === NULL) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
