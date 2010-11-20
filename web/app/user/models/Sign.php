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
 * @category   User
 * @package    User_Model
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Sign.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');


/**
 * Class User_Model_Sign
 *
 * @category   User
 * @package    User_Model
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class User_Model_Sign
{
    public function in($params) 
    {
        try {
        
            $_user = Core_Dao::factory(array('name' => 'user'));
            
            $where = array('uname' => $params['uname']);

            $rs = $_user->getList($where, array(), 1);

            if (isset($rs[0]['uname'])) {
                $user = $rs[0];
            } else {
                throw new Exception('No items found');
            }
            
        } catch (Exception $e) {
            throw $e;
        }

        if (!isset($user['pass'])) {
            throw new Exception('Username and pass do not match');
        }
        
        $pass = md5($params['pass']);
        if ($pass != $user['pass']) {
            throw new Exception('Username and pass do not match');
        }

        $sid = Core_Util_Uuid::create();
        $timeout = 365 * 24 * 60 * 60;  
        $data = array('id' => $sid,
            'uid'    => $user['id'],
            'uname'  => $user['uname'],
            'persistent'=> $params['persistent'],
            'source'    => Core_Util_Ip::getRemoteAddr());

        try {
            $_session = Core_Dao::factory(array('name' => 'user_session'));
            $_session->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
        
        $_SESSION['sid'] = $sid;
        $_SESSION['uid'] = $user['id'];
        setcookie("sid", $sid, time() + $timeout, '/');
        setcookie("uid", $user['id'], time() + $timeout, '/');
    }
    
    public function out()
    {
        if (isset($_SESSION['sid'])) {
            $sid = $_SESSION['sid'];
        } else if (isset($_COOKIE['sid'])) {
            $sid = $_COOKIE['sid'];
        } else {
            $sid = null;
        }

        if (strlen($sid) == 36) {
            try {
                $_session = Core_Dao::factory(array('name' => 'user_session'));
                $_session->delete(array('id' => $sid));
            } catch (Exception $e) {
                throw $e;
            }
        }
        
        @session_destroy();
        setcookie("sid", '', time() - 3600, '/');
        setcookie("uid", '', time() - 3600, '/');
    }
    
    public function up($params)
    {
        try {
            $_user = Core_Dao::factory(array('name' => 'user'));
            
            $where = array('uname' => $params['uname']);

            $rs = $_user->getList($where, array(), 1);

            if (isset($rs[0]['uname'])) {
                throw new Exception('This ID is not available, please use another one');
            }
            
            $pass = md5($params['pass']);

            $user = array('uname' => $params['uname'],
                'email' => $params['email'],
                'pass' => $pass);
        
            $_user->insert($user);
            
        } catch (Exception $e) {
            throw $e;
        }
    }
 
}
