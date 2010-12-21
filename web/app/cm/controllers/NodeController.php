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
 * @version    $Id: NodeController.php 856 2010-05-07 16:05:39Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class NodeController
 *
 * @category   Cm
 * @package    NodeController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class NodeController extends Core_Controller
{
    public function init()
    {
        $this->initdb();
    }
    
    public function listAction()
    {
    }
    
    public function newAction()
    {
        $this->editAction();
    }
    
    public function editAction()
    {
        
        //$table = isset($params['data_base']) ? $params['data_base'] : 'data_entry';

        $cfgcm = Core_Config::get("cm");

        $entry = $this->reqs->params;
        $id     = isset($entry['id']) ? $entry['id'] : '';

        $dbentry= Core_Dao::factory(array('name' => 'data_entry'));
        $entry  = $dbentry->getById($id);

        $dbcat  = Core_Dao::factory(array('name' => 'taxonomy_term_user'));
        $cats   = $dbcat->getList(array('uid' => 1, 'vid' => 1), array(), 1000);

        if (!strlen($entry['terms'])) {
            $entry['terms'] = "";
        }
        //$entry['published'] = date("Y:m:d h:i:s", strtotime($entry['published']));
        //$entry['updated']   = date("Y:m:d h:i:s", strtotime($entry['updated']));

        $params = array('reqs' => $this->reqs,
            'entry' => $entry,
            'cats' => $cats,
        );
        $this->view->content = $this->view->render('node/edit', $params);

        $this->response('app/layout');
    }
    
    public function saveAction()
    {
        $farr = array(
            //"/\s&nbsp;/",
            "/<(\/?)(script|iframe|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z] \s*=([^>]*>)/isU",
        );
        $tarr = array(
            //" ",
            "&lt;\\1\\2\\3&gt;",
            "\\1\\2",
        );
        $entry = $this->reqs->params;
        
        $entry['content'] = preg_replace($farr, $tarr, $entry['content']);
        
        if (! isset($entry['auto_summary']) && isset($entry['summary']) && strlen(trim($entry['summary']))) {
            $entry['summary'] = preg_replace($farr, $tarr, $entry['summary']);
        } else {
            $entry['summary'] = NULL;
        }
        
        $validator = new Cm_Model_EntryValidate();
        if (! $validator->isValid($entry, $message)) {
            $this->view->message = Core_Message::get('error', $message);
            return $this->editAction();
        }
        
        
        $dbentry= Core_Dao::factory(array('name' => 'data_entry'));
        
        try {
            if ($entry['id'] == "") {
                $entry['id'] = Core_Util_Uuid::create(); 
                $dbentry->insert($entry);
            } else {
                $where = array('id' => $entry['id']);
                //unset($entry['id']);
                $dbentry->update($entry, $where);
            }
        } catch (Exception $e) {
            $this->view->message = Core_Message::get('error', $e->getMessage());
            return $this->editAction();
        }
        
        $this->reqs->params = $entry;
        $this->view->message = Core_Message::get('success', 'Success');
        $this->editAction();
    }
}
