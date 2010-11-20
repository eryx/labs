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
 * @package    ProfileManageController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: ManageProfileController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class ProfileManageController
 *
 * @category   User
 * @package    ProfileManageController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class ProfileManageController extends Core_Controller
{
    public function init()
    {
        $this->initdb();
        $this->session = Core_Session::getInstance();
    }
    
    public function indexAction()
    {
        $item = array();
        
        try {
        
            if ($this->session->uid != "0") {
                
                $_db = Core_Dao::factory(array('name' => 'user_profile'));
                
                $item = $_db->getById($this->session->uid);
                if (isset($item['id'])) {
                    $item['content'] = Core_Util_Format::richEditFilter($item['content']);
                    $item['content'] = htmlspecialchars($item['content'], ENT_NOQUOTES);
                } else {
                    $item = array();
                }
            }
        } catch (Exception $e) {
            //
        }
        
        $entry = new Core_Object();
        foreach ($item as $key => $val) {
            $entry->$key = $val;
        }
        
        $this->view->content = $this->view->render('profile-manage/index', array('entry' => $entry));
        $this->response('layout');
    }
    
    public function doAction()
    {
        $vars = get_object_vars($this->reqs);

		if (User_Model_Profile_Validate::isValid($vars, $message)) {
		
		    try {
		        $_pf = Core_Dao::factory(array('name' => 'user_profile'));
		        $item = $_pf->getById($this->session->uid);
		        
                if (isset($item['id'])) {
                    $where = array('id' => $this->session->uid);
                    $_pf->update($vars, $where);
                } else {
                    $vars['id'] = $this->session->uid;
                    $vars['uname'] = $this->session->uname;
                    $_pf->insert($vars);
                }
                
                $msg = Core_Message::get('success', 'OK');
            } catch (Exception $e) {
                $msg = Core_Message::get('error', 'ERROR'.$e->getMessage());
            }
            
        } else {
		    $msg = Core_Message::get('error', $message);
		}
        
        $vars['content'] = Core_Util_Format::richEditFilter($vars['content']);
        $vars['content'] = htmlspecialchars($vars['content'], ENT_NOQUOTES);
        
        $this->view->profile = $vars;
        
        if (isset($msg)) {
            $this->view->message = $msg;
        }

        $entry = new Core_Object();
        foreach ($vars as $key => $val) {
            $entry->$key = $val;
        }
        $this->view->content = $this->view->render('profile-manage/index', array('entry' => $entry));
        $this->response('layout');
    }
    
    public function photoAction()
    {
        $_user = Core_Dao::factory(array('name' => 'user'));
        
        $uname = $this->session->uname;
        
        $des = str_split($uname);            
        $path = '/data/user/'.$des['0'].'/'.$des['1'].'/'.$des['2'].'/'.$uname;
    
        if (!file_exists(SYS_ROOT.$path."/w100.png")) {
            $path = '/data/user';
        }
    
        $this->view->entry_path = $path;
        $this->view->content = $this->view->render('profile-manage/photo');
        $this->response('layout');
    }
        
    public function photodoAction()
    {
        $vars = get_object_vars($this->reqs);

        $_user = Core_Dao::factory(array('name' => 'user'));
        $_image = new Core_Util_Image();
        
        $status = true;
        
        $profile = null;
        try {        
            if ($this->session->uid != "0") {
                $profile = $_user->getById($this->session->uid);
            }            
        } catch (Exception $e) {
            //
        }
        
        if ($profile === null) {
            $msg = Core_Message::get('error', 'Unknown error');
        } else {
        
            $file_tmp  = $_FILES['attachment']['tmp_name'];
            $file_name = $_FILES['attachment']['name'];
            $file_size = $_FILES['attachment']['size'];
            $file_mime = $_FILES['attachment']['type'];
            
            $file_ext  = substr(strrchr(strtolower($file_name), '.'), 1);
            if (! in_array($file_ext, array('png', 'jpg', 'jpeg', 'gif'))) {
                $msg = Core_Message::get('error', 'You must upload a JPG, GIF, or PNG file');
            } else if (is_uploaded_file($file_tmp)) {
            
                $des = str_split($profile['uname']);            
                $des_dir = SYS_ROOT.'/data/user/'.$des['0'].'/'.$des['1'].'/'.$des['2'];
                $des_dir.= '/'.$profile['uname'];
            
                Core_Util_Directory::mkdir($des_dir);
            
                $file_size_stored = @filesize($file_tmp);
            
                if ($file_size_stored > 1000000) {
                    @unlink($file_tmp);
                    $max_size = 1000000 / 1000;
                    $msg = Core_Message::get('error', "File size must less than $max_size Kb");
                    $status =  false;
                } elseif ($file_size_stored != $file_size) {
                    @unlink($file_tmp);
                    $msg = Core_Message::get('error', 'Unknown error');
                    $status =  false;
                }
            
                if ($status && $imginfo = @getimagesize($file_tmp)) {
                    if (!$imginfo[2]) {
                        @unlink($file_tmp);
                        $msg = Core_Message::get('error', 'Invalid image');
                        $status =  false;
                    }
                }
            
                $_image->resampimagejpg(100, 100, $file_tmp, $des_dir.'/w100.png', true);
                $_image->resampimagejpg(40, 40, $file_tmp, $des_dir.'/w40.png', false);            
            }
        }
        
        if (isset($msg)) {
            $this->view->message = $msg;
        } else {
            $this->view->message = Core_Message::get('success', 'Success');
        }
        
        $this->photoAction();
    }
    
}
