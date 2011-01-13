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
 * @category   Cm
 * @package    Cm_Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class UserController
 *
 * @category   Cm
 * @package    UserController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class UserController extends Core_Controller
{
    public $user = NULL;
    
    public function init()
    {
        $this->initdb();
        
        if (!isset($this->reqs->uname)) {
            die('TODO EXCEPTION:'.__FILE__.':'.__LINE__);
        }
        
        $uid = Core_User::name2id($this->reqs->uname);
        
        // Profile
        $dbup   = Core_Dao::factory(array('name' => 'user_profile'));
        $item   = $dbup->getByID($uid);
        if (!isset($item['id'])) {
            die('TODO EXCEPTION:'.__FILE__.':'.__LINE__);
        }
        $this->user = new Core_Object();
        $this->user->profile = $item;
                
        // Products
        $dbua   = Core_Dao::factory(array('name' => 'user_apps'));
        $where  = array('uid' => $uid, 'isfrontend' => 1);
        $items  = $dbua->getList($where, array(), 100);
        if (count($items) == 0) {
            die('TODO EXCEPTION:'.__FILE__.':'.__LINE__);
        }
        $this->user->apps = $items;
    }
    
    public function indexAction()
    {
        $cfg = require SYS_ROOT."/conf/cm.user.php";

        $this->view->inst = $this->reqs->inst;
        
        if (!isset($cfg['pagelets'][$this->reqs->act])) {
            return $this->errorAction();
        }
        $this->view->act = $this->reqs->act;

        $params = array('reqs' => $this->reqs);
        foreach ($cfg['pagelets'][$this->reqs->method]['views'] as $v) {
            
            if (isset($v['params'])) {
                $params['params'] = $v['params'];
            } else {
                $params['params'] = array();
            }
            
            $this->view->{$v['pagelet']} = $this->view->render($v['script'], $params);
        }
        
        $this->view->profile    = $this->user->profile;
        $this->view->apps       = $this->user->apps;
        $this->view->reqs       = $this->reqs;
        
        $this->response($cfg['pagelets'][$this->reqs->act]['layout']);
    }
    
    public function listAction()
    {
    }
    
    public function viewAction()
    {
    }
}
