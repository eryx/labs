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
 * @version    $Id: ManageController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class ManageController
 *
 * @category   User
 * @package    Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class ManageController extends Core_Controller
{
    public function init()
    {
        $this->initdb();
        $this->session = Core_Session::getInstance();
        $this->view->session = $this->session;
    }
    
    public function indexAction()
    {   
        try {
            
            if ($this->session->uid != "0") {
                $_user = Core_Dao::factory(array('name' => 'user'));
                $this->view->user = $_user->getById($this->session->uid);
                
                $des = str_split($this->session->uname);            
                $path = '/data/user/'.$des['0'].'/'.$des['1'].'/'.$des['2'].'/'.$this->session->uname;
    
                if (!file_exists(SYS_ROOT.$path."/w100.png")) {
                    $path = '/data/user';
                }
    
                $this->view->user['photo_path'] = $path;
        
                $this->view->content = $this->view->render('manage/index');
            } else {
                throw new Exception('Access Denied');
            }
            
        } catch (Exception $e) {
            $this->view->message = Core_Message::get('error', $e->getMessage());
        }
        
        unset($this->session);
        
        $this->response('layout');
    }

}
