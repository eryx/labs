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
        
            $table = isset($v['table']) ? $v['table'] : 'data_entry';

            $db = Core_Dao::factory(array('name' => $table));
            $where = array();
            $output = array();
            $limit = 10;
            
            if (isset($v['query'])) {
                foreach ($v['query'] as $key => $val) {
                    if (isset($this->reqs->{$key})) {
                        $where[$key] = $this->reqs->{$key};
                    }
                }
            }
            
            if ($v['output'] == 'entry') {
                if (isset($where['id'])) {
                    $rs = $db->getById($where['id']);
                } else {
                    $rs = array();
                }
            } else {
                $order = isset($v['sortby']) ? $v['sortby'] : array();
                $p  = isset($this->reqs->p) ? intval($this->reqs->p) : 1;
                if ($p < 1) {
                    $p = 1;
                }
                $rs = $db->getList($where, $order, $limit, ($p - 1) * $limit);
                
                if (isset($v['pager'])) {
                    $count = $db->getCount($where);
                }
            }
            
            $output[$v['output']] = $rs;
            
            if (isset($v['pager'])) {
                $count = $db->getCount($where);
                $output['pager'] = Core_Util_Pager::get($p, $count, $limit);
            }

            $this->view->{$v['laykey']} = $this->view->render($v['view'], $output);

            unset($db, $rs);
        }
        
        $this->response($c['pagelets'][$this->reqs->act]['layout']);
    }
    
    public function errorAction()
    {
        echo 'ERROR';
    }
    
    public function testAction()
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
}
