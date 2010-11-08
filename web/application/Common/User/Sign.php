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
 * @package    Common_User
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Sign.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');


/**
 * @see Common_Util_Ip
 */
require_once 'Common/Util/Ip.php';


/**
 * Class Common_User_Sign
 *
 * @category   Common
 * @package    Common_User
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Common_User_Sign
{

    public function in($params) 
    {
        $_user = new Common_Db_User();

        try {
            $user = $_user->getByName($params['uname']);
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

        $sid = Common_Util_Uuid::create();
        $timeout = 365 * 24 * 60 * 60;  
        $data = array('sid' => $sid,
            'uid'    => $user['uid'],
            'uname'  => $user['uname'],
            'persistent'=> $params['persistent'],
            'source'    => Common_Util_Ip::getRemoteAddr());
        
        try {
            $_session = new Common_Db_Session();  
            $_session->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
        
        $_SESSION['sid'] = $sid;
        $_SESSION['uid'] = $user['uid'];
        setcookie("sid", $sid, time() + $timeout, '/');
        setcookie("uid", $user['uid'], time() + $timeout, '/');
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
                $_session = new Common_Db_Session();
                $_session->delete($sid);
            } catch (Exception $e) {
                //
            }
        }
        
        @session_destroy();
        setcookie("sid", '', time() - 3600, '/');
        setcookie("uid", '', time() - 3600, '/');
    }
    
    public function up($params)
    {
        $_user = new Common_Db_User();

        try {
            $user = $_user->getByName($params['uname']);
        } catch (Exception $e) {
            $user = array();
        }
        
        if (isset($user['uname'])) {
            throw new Exception('This ID is not available, please use another one');
        }
        
        $pass = md5($params['pass']);

        $uid = Common_Util_Uuid::create();

        $user = array('uid' => $uid,
            'uname' => $params['uname'],
            'email' => $params['email'],
            'pass' => $pass);
        
        try {
            $_user->insert($user);
        } catch (Exception $e) {
            throw $e;
        }

    }
 
}
