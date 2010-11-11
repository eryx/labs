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
 * @version    $Id: SignController.php 832 2010-03-21 15:51:48Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class User_SignController
 *
 * @category   User
 * @package    User_Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class User_SignController extends Common_Controller_Action
{
    
    public function indexAction()
    {
        if (isset($this->_params['uname'])) {
            $this->view->uname = $this->_params['uname'];
        }
        $this->loadLayout('layout-simple');
        $this->render('index');
    }

    public function postAction()
    {
        $params = $this->_getAllParams();
        
        $_validator = new Common_User_Sign_Validate();
        if (!$_validator->isValid($params, $msg)) {
            $this->view->message = Common_Message::get('error', $msg);
            return $this->indexAction();
        }
        
        try {
            $_sign = new Common_User_Sign();
            $_sign->in($params);
        } catch (Exception $e) {
            $this->view->message = Common_Message::get('error', $e->getMessage());
            //return $this->indexAction();
        }


        $this->loadLayout('layout-simple');
        $this->render('index');
        //$this->_redirect('/');
        return;
    }
    
    public function outAction()
    {
        try {
            $_sign = new Common_User_Sign();
            $_sign->out();
        } catch (Exception $e) {
        
        }
        
        $this->_redirect('/');
        return;
    }
    
    public function upAction()
    {
        $this->loadLayout('layout-simple');
    }

    public function uppostAction()
    {
        foreach ($this->_params as $key => $value) {
            $this->view->$key = $value;
        }

        $_validator = new Common_User_Sign_UpValidate();
        if (!$_validator->isValid($this->_params, $msg)) {
            $this->view->message = Common_Message::get('error', $msg);
            return $this->indexAction();
        }
    
        try {
            $_sign = new Common_User_Sign();
             $_sign->up($this->_params);
        
            $links = array(array('url' => $this->view->baseModulePath.'/sign',
                'title' => 'Sign in'));
            
            $this->view->message = Common_Message::get('success', 'Success', $links);
        
            $this->render('message', null, true);
        
        } catch (Exception $e) {
            $this->view->message = Common_Message::get('error', $e->getMessage());
            return $this->indexAction();
        }
    }  
}
