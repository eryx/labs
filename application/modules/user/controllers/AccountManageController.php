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
 * @package    User_Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: AccountManageController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class User_AccountManageController
 *
 * @category   User
 * @package    User_Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class User_AccountManageController extends Common_Controller_Action
{    
    public function pwdAction()
    {
        $this->loadLayout('layout-simple');
    }
    
    public function pwddoAction()
    {
        $params = $this->_params;
        
        if (User_Model_Account_Validate::isPasswordChange($params, $msg)) {

            try {
           
                if ($this->_session->uid != "0") {

                    $_user = new Common_Db_User();
                    $user = $_user->getById($this->_session->uid);

                    if (isset($user['pass'])
                        && md5($params['pass_current']) == $user['pass']) {
                        $set = array('pass' => md5($params['pass']));
                        $where = array('uid = ?' => $this->_session->uid);
                        $_user->update($set, $where);
                        $this->view->message = Common_Message::get('success', 'Success');
                    } else {
                        $this->view->message = Common_Message::get('error',
                            'Current password do not match');
                    }
                }
            } catch (Exception $e) {
                $this->view->message = Common_Message::get('error',
                            'Current password do not match');
            }
            
        } else {
            $this->view->message = Common_Message::get('error', $msg);
        }

        $this->loadLayout('layout-simple');
        $this->render('pwd');
    }

    public function emailAction()
    {
        $_user = new User_Model_Db_User();
        
        try {
        
            if ($this->_session->uid != "0") {
                $pf = $_user->getById($this->_session->uid);
            } else {
                $pf = null;
            }
        } catch (Exception $e) {
            $pf = null;
        }
        
        $this->view->profile = $pf;
        
        $this->loadLayout('layout-simple');
        $this->render('email');
    }
    
    public function emaildoAction()
    {
        $params = $this->_params;
        
        if (User_Model_Account_EmailValidate::isValid($params, $msg)) {

            try {
           
                if ($this->_session->uid != "0") {

                    $_user = new Common_Db_User();
                    //$user = $_user->getById($this->_session->uid);

                    $set = array('email' => $params['email']);
                    $where = array('uid = ?' => $this->_session->uid);
                    $_user->update($set, $where);
                    $this->view->message = Common_Message::get('success', 'Success');
                    
                }
            } catch (Exception $e) {
                $this->view->message = Common_Message::get('error', 'Unknown');
            }
            
        } else {
            $this->view->message = Common_Message::get('error', $msg);
        }

        $this->loadLayout('layout-simple');
        $this->render('email');
    }
}
