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
 * @version    $Id: SignController.php 832 2010-03-21 15:51:48Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class SignController
 *
 * @category   User
 * @package    Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class SignController extends Core_Controller
{
    public function indexAction()
    {
        $this->view->uname = isset($this->reqs->uname) ? $this->reqs->uname : "";
        $this->response('sign/index');
    }

    public function indoAction()
    {
        $vars = get_object_vars($this->reqs);
        
        if (!User_Model_Sign_Validate::isValid($vars, $msg)) {
            $this->view->message = Core_Message::get('error', $msg);
            return $this->indexAction();
        }
        
        try {
            $this->initdb();
            $_sign = new User_Model_Sign();
            $_sign->in($vars);
        } catch (Exception $e) {
            $this->view->message = Core_Message::get('error', $e->getMessage());
            return $this->indexAction();
        }

        if (isset($vars['continue'])) {
            header("Location: {$vars['continue']}");
        } else {
            header("Location: /");
        }
    }
    
    public function outAction()
    {
        try {
            $this->initdb();
            $_sign = new User_Model_Sign();
            $_sign->out();
        } catch (Exception $e) {
            //
        }
        
        if (isset($this->reqs->continue)) {
            header("Location: {$this->reqs->continue}");
        } else {
            header("Location: /");
        }
    }
    
    public function upAction()
    {
        $vars = get_object_vars($this->reqs);
        foreach ($vars as $key => $value) {
            $this->view->$key = $value;
        }
        
        $this->response('sign/up');
    }

    public function updoAction()
    {
        $vars = get_object_vars($this->reqs);
        
        if (!User_Model_Sign_UpValidate::isValid($vars, $msg)) {
            $this->view->message = Core_Message::get('error', $msg);
            return $this->upAction();
        }
    
        try {
            $this->initdb();
            
            $_sign = new User_Model_Sign();
            $_sign->up($vars);
            
            $this->view->message = Core_Message::get('success', 'Success');
            
            $this->indexAction();
        
        } catch (Exception $e) {
            $this->view->message = Core_Message::get('error', $e->getMessage());
            $this->upAction();
        }
    }
}
