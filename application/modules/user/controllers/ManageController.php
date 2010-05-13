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
 * @version    $Id: ManageController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class User_ManageController
 *
 * @category   User
 * @package    User_Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class User_ManageController extends Common_Controller_Action
{
    
    public function indexAction()
    {
        $_user = new Common_Db_User();
        
        try {
        
            if ($this->_session->uid != "0") {
                $user = $_user->getById($this->_session->uid);
            } else {
                $user = null;
            }
        } catch (Exception $e) {
            $user = null;
        }
        
        $this->view->item = $user;
        $this->loadLayout('layout-simple');
        $this->render('index');
    }

}
