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
 * @package    Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: AccountController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class AccountManageController
 *
 * @category   User
 * @package    Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class AccountManageController extends Core_Controller
{
    public function init()
    {
        $this->initdb();
        $this->session = Core_Session::getInstance();
    }
    
    public function pwdAction()
    {
        $this->view->content = $this->view->render('account-manage/pwd');
        $this->response('layout');
    }
    
    public function pwddoAction()
    {
        $vars = get_object_vars($this->reqs);
        
        if (User_Model_Account_Validate::isPasswordValid($vars, $msg)) {

            try {
           
                if ($this->session->uid != "0") {
                    
                    $_user = Core_Dao::factory(array('name' => 'user'));
                    $user = $_user->getById($this->session->uid);

                    if (isset($user['pass'])
                        && md5($vars['pass_current']) == $user['pass']) {
                        $set = array('pass' => md5($vars['pass']));
                        $where = array('id' => $this->session->uid);
                        $_user->update($set, $where);
                        $this->view->message = Core_Message::get('success', 'Success');
                    } else {
                        $this->view->message = Core_Message::get('error',
                            'Current password do not match');
                    }
                }
            } catch (Exception $e) {
                $this->view->message = Core_Message::get('error',
                            'Current password do not match');
            }
            
        } else {
            $this->view->message = Core_Message::get('error', $msg);
        }

        $this->pwdAction();
    }

    public function emailAction()
    {
        $_user = Core_Dao::factory(array('name' => 'user'));
        
        try {
        
            if ($this->session->uid != "0") {
                $user = $_user->getById($this->session->uid);
            } else {
                $user = null;
            }
        } catch (Exception $e) {
            $user = null;
        }
        
        if ($user === NULL) {
            $this->view->message = Core_Message::get('error', 'Access Denied');
        } else {
            $this->view->content = $this->view->render('account-manage/email', 
                array('entry' => $user));
        }
        
        $this->response('layout');
    }
    
    public function emaildoAction()
    {
        $vars = get_object_vars($this->reqs);
        
        if (!User_Model_Account_EmailValidate::isValid($vars, $msg)) {
            $this->view->message = Core_Message::get('error', $msg);
            return $this->emailAction();
        }

        try {
           
            if ($this->session->uid != "0") {

                $_user = Core_Dao::factory(array('name' => 'user'));

                $user = $_user->getById($this->session->uid);

                if (isset($user['pass']) && md5($vars['pass']) == $user['pass']) {
                    $set = array('email' => $vars['email']);
                    $where = array('id' => $this->session->uid);
                    $_user->update($set, $where);
                    $this->view->message = Core_Message::get('success', 'Success');
                } else {
                    $this->view->message = Core_Message::get('error',
                        'Password do not match');
                }
            }
        } catch (Exception $e) {
            $this->view->message = Core_Message::get('error', 'Unknown');
        }
        
        $this->emailAction();
    }
}
