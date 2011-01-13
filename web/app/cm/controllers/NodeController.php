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
    
    public function delAction()
    {
        $links = array(array(
            'url' => 'javascript:history.back();',
            'title' => 'Back',
        ));
        
        if (!isset($this->reqs->params['id'])) {
            $this->view->message = Core_Message::get('error', "ID can not be null", $links);
            return $this->response("error/index");
        }
        
        try {
            $dbentry= Core_Dao::factory(array('name' => 'data_entry'));
            $entry  = $dbentry->getById($this->reqs->params['id']);
            if (!isset($entry['id'])) {
                throw new Exception('Entry not found');
            }
            
            $dbentry->delete(array('id' => $this->reqs->params['id']));
            
        } catch (Exception $e) {
            $this->view->message = Core_Message::get('error', $e->getMessage());
            return $this->response("error/index");
        }
        
        $this->view->message = Core_Message::get('success', 'Success');
        $this->response("error/index");
    }
    
    public function debugAction()
    {
        try {

            if (isset($GLOBALS['config']['database2'])) {
                $dbsrc = Zend_Db::factory($GLOBALS['config']['database2']['adapter'],
                    $GLOBALS['config']['database2']['params']);

            } else {
                throw new Exception('Can not connect to db-server');
            }
            
            

        } catch (Exception $e) {
            $e->getMessage();
        }
        
        $dbentry= Core_Dao::factory(array('name' => 'data_entry'));
        $dbtermuser= Core_Dao::factory(array('name' => 'taxonomy_term_user'));
        $db = $dbentry->getAdapter();

        $rs = $dbsrc->query("SELECT a.*,u.username from kit_node a,kit_user u WHERE a.userid = u.userid ORDER BY nodeid LIMIT 99999")->fetchAll();
        foreach ($rs as $val) {
        
            $uid = Core_User::name2id($val['username']);
            
            $set = array('id' => $val['nodeid'],
                'uid' => $uid,
                'uname' => $val['username'],
                'cat' => $val['treeid'],
                'type' => $val['module'],
                'status' => $val['status'],
                'title' => $val['title'],
                'terms' => $val['terms'],
                'created' => $val['created'],
                'updated' => $val['modified'],
                'published' => $val['created']);

            $dbentry->insert($set);
        }
        
        $rs = $dbsrc->query("SELECT * from kit_node_revision ORDER BY nodeid LIMIT 99999")->fetchAll();
        $counter = 0;
        foreach ($rs as $val) {
        
            $str = @mb_convert_encoding($val['body'], 'UTF-8', mb_detect_encoding($val['body'], "auto", TRUE));
            if ($str === FALSE || $str === NULL || $str == "") {
                $counter++;
            }
            if (strlen(time($str)) < 10) {
                $counter++;
            }
            $set = array(
                'summary' => $val['summary'],
                'content' => $str
            );

            try {
                $dbentry->update($set, array('id' => $val['nodeid']));
            } catch (Exception $e) {
                $counter++;
            }
        }
        echo $counter;
        
        $dbtype = Core_Dao::factory(array('name' => 'data_type'));
        $rs = $dbsrc->query("SELECT * from kit_system_module ORDER BY moduleid LIMIT 99999")->fetchAll();
        foreach ($rs as $val) {
        
            $title = @mb_convert_encoding($val['name'], 'UTF-8', mb_detect_encoding($val['name'], "auto", TRUE));
            $description = @mb_convert_encoding($val['description'], 'UTF-8', mb_detect_encoding($val['description'], "auto", TRUE));
            
            $set = array('id' => $val['submodule'],
                'title' => $title,
                'summary' => $description,
                'created' => $val['created'],
                'updated' => $val['modified'],
                'isfrontend' => $val['isfrontend'],
                'isbackend' => $val['isbackend'],
                'isenabled' => $val['isenabled'],
                'isuserenabled' => $val['isuserenabled'],
                'isrequired' => $val['isrequired']);
           
            $dbtype->insert($set);
        }
        
        $rs = $dbsrc->query("SELECT a.*,u.username from kit_node_tree a,kit_user u WHERE a.userid = u.userid ORDER BY treeid LIMIT 99999")->fetchAll();
        $counter = 0;
        foreach ($rs as $val) {
        
            $str = @mb_convert_encoding($val['name'], 'UTF-8', mb_detect_encoding($val['name'], "auto", TRUE));
            
            $uid = Core_User::name2id($val['username']);
            
            $set = array('id' => $val['treeid'],
                'uid' => $uid,
                'pid' => $val['parentid'],
                'title' => $str,
                'created' => $val['created'],
                'updated' => $val['modified'],
                'weight' => $val['ordering'],
                'app' => $val['module']);
           
            
            try {
                $dbtermuser->insert($set);
            } catch (Exception $e) {
                $counter++;
            }
        }
        echo $counter;
        
        $dbuser = Core_Dao::factory(array('name' => 'user'));
        $dbuserp = Core_Dao::factory(array('name' => 'user_profile'));
        $rs = $dbsrc->query("SELECT * from kit_user ORDER BY userid LIMIT 99999")->fetchAll();
        foreach ($rs as $val) {
        
            $name = @mb_convert_encoding($val['name'], 'UTF-8', mb_detect_encoding($val['name'], "auto", TRUE));
            $content = @mb_convert_encoding($val['aboutme'], 'UTF-8', mb_detect_encoding($val['aboutme'], "auto", TRUE));
            $home_name = @mb_convert_encoding($val['home_name'], 'UTF-8', mb_detect_encoding($val['home_name'], "auto", TRUE));
            
            $uid = Core_User::name2id($val['username']);
            
            $set = array('id' => $uid,
                'uname' => $val['username'],
                'pass' => $val['password'],
                'email' => $val['email'],
                'name' => $name,
                'created' => $val['created'],
                'updated' => $val['modified']);
            $dbuser->insert($set);

            
            $set = array('id' => $uid,
                'gender' => $val['gender'],
                'birthday' => $val['birthday'],
                'name' => $name,
                'address' => $val['address'],
                'content' => $content,
                'uname' => $val['username'],
                'home_name' => $home_name,
                'created' => $val['created'],
                'updated' => $val['modified']);
            $dbuserp->insert($set);
        }
        
        
        $dbusera = Core_Dao::factory(array('name' => 'user_apps'));
        $rs = $dbsrc->query("SELECT a.*,u.username from kit_user_module a,kit_user u WHERE a.userid = u.userid ORDER BY id LIMIT 99999")->fetchAll();
        foreach ($rs as $val) {
            
            $uid = Core_User::name2id($val['username']);
            
            $set = array('id' => $val['id'],
                'uid' => $uid,
                'app' => $val['submodule'],
                'title' => $val['name'],
                'isfrontend' => $val['isfrontend'],
                'isbackend' => $val['isbackend'],
                'isenabled' => $val['isenabled'],
                'created' => $val['created'],
                'updated' => $val['modified']);
            $dbusera->insert($set);

        }
        
        

    }
}
