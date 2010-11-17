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
 * @version    $Id: IndexController.php 856 2010-05-07 16:05:39Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class IndexController
 *
 * @category   Cm
 * @package    IndexController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class IndexController extends Core_Controller
{
    public function init()
    {
        $this->initdb();
    }
    
    public function indexAction()
    {
        $conf = require SYS_ROOT."/conf/cm.{$this->reqs->instance}.inc.php";
        $conf = $conf[$this->reqs->method];

        foreach ($conf['views'] as $v) {
        
            $datax = $v['data']['datax'];            
            $db = Core_Dao::factory(array('name' => $datax));
            $rs = $db->getList();

            $this->view->{$v['name']} = $this->view->render($v['view'], 
                array($v['data']['output'] => $rs));
            
            unset($db, $rs);
        }
        
        $this->response('list');
    }
    
    public function testAction()
    {
        //
        $data['feeds'][] = array('title' => 'Title 1');
        $data['feeds'][] = array('title' => 'Title 2');
        $data = $this->view->render("index/index", $data);
        $this->view->content = $data;
        //
        $this->response('list');
    }
}
