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
 * @version    $Id: AppController.php 856 2010-05-07 16:05:39Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class AppController
 *
 * @category   Cm
 * @package    AppController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class AppController extends Core_Controller
{
    public function init()
    {
        $this->initdb();
    }
    
    public function indexAction()
    {
        $c = require SYS_ROOT."/conf/cm.php";
        
        if (!in_array($this->reqs->inst, $c['types'])) {
            return $this->errorAction();
        }
        $this->view->inst = $this->reqs->inst;
        
        if (!isset($c['pagelets'][$this->reqs->act])) {
            return $this->errorAction();
        }
        $this->view->act = $this->reqs->act;

        foreach ($c['pagelets'][$this->reqs->act]['views'] as $v) {
            $this->view->{$v['laykey']} = $this->view->render($v['view']);
        }
        
        $this->response($c['pagelets'][$this->reqs->act]['layout']);
    }
}
