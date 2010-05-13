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
 * @package    User_ProfileManageController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: ProfileManageController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class User_ProfileManageController
 *
 * @category   User
 * @package    User_ProfileManageController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class User_ProfileManageController extends Common_Controller_Action
{
    
    public function indexAction()
    {
        $_db = new User_Model_Db_UserProfile();

        try {
        
            if ($this->_session->uid != "0") {
                $pf = $_db->getById($this->_session->uid);
                $pf['desc'] = Common_Util_Format::richEditFilter($pf['desc']);
                $pf['desc'] = htmlspecialchars($pf['desc'], ENT_NOQUOTES);
            } else {
                $pf = null;
            }
        } catch (Exception $e) {
            $pf = null;
        }
        
        $this->view->profile = $pf;
        
        $this->loadLayout('layout-simple');
    }
    
    public function doAction()
    {
        $params = $this->_params;

		if (User_Model_Profile_Validate::isValid($params, $message)) {
		
		    try {
		        $_pf = new User_Model_Profile();
		        $where = array('uid = ?' => $this->_session->uid);
                $_pf->update($params, $where);
                $msg = Common_Message::get('success', 'OK');
            } catch (Exception $e) {
                $msg = Common_Message::get('error', 'ERROR'.$e->getMessage());
            }
            
        } else {
		    $msg = Common_Message::get('error', $message);
		}
        
        $params['desc'] = Common_Util_Format::richEditFilter($params['desc']);
        $params['desc'] = htmlspecialchars($params['desc'], ENT_NOQUOTES);
        
        $this->view->profile = $params;
        
        if (isset($msg)) {
            $this->view->message = $msg;
        }

        $this->loadLayout('layout-simple');
        $this->render('index');
    }
    
    public function photoAction()
    {        
        $this->loadLayout('layout-simple');
    }
        
    public function photodoAction()
    {
        $params = $this->_params;

        $_user = new Common_Db_User();
        $_image = new Common_Util_Image();
        
        $status = true;
        
        $profile = null;
        try {        
            if ($this->_session->uid != "0") {
                $profile = $_user->getById($this->_session->uid);
            }            
        } catch (Exception $e) {
            //
        }
        
        if ($profile === null) {
            $msg = Common_Message::get('error', 'Unknown error');
        } else {
        
            $file_tmp  = $_FILES['attachment']['tmp_name'];
            $file_name = $_FILES['attachment']['name'];
            $file_size = $_FILES['attachment']['size'];
            $file_mime = $_FILES['attachment']['type'];
            
            $file_ext  = substr(strrchr(strtolower($file_name), '.'), 1);
            if (! in_array($file_ext, array('png', 'jpg', 'jpeg', 'gif'))) {
                $msg = Common_Message::get('error', 'You must upload a JPG, GIF, or PNG file');
            } else if (is_uploaded_file($file_tmp)) {
            
                $des = str_split($profile['uname']);            
                $des_dir = SYS_ENTRY.'/data/user/'.$des['0'].'/'.$des['1'].'/'.$des['2'];
                $des_dir.= '/'.$profile['uname'];
            
                Common_Util_Directory::mkdir($des_dir);
            
                $file_size_stored = @filesize($file_tmp);
            
                if ($file_size_stored > 1000000) {
                    @unlink($file_tmp);
                    $max_size = 1000000 / 1000;
                    $msg = Common_Message::get('error', "File size must less than $max_size Kb");
                    $status =  false;
                } elseif ($file_size_stored != $file_size) {
                    @unlink($file_tmp);
                    $msg = Common_Message::get('error', 'Unknown error');
                    $status =  false;
                }
            
                if ($status && $imginfo = @getimagesize($file_tmp)) {
                    if (!$imginfo[2]) {
                        @unlink($file_tmp);
                        $msg = Common_Message::get('error', 'Invalid image');
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
            $this->view->message = Common_Message::get('success', 'Success');
        }
        
        $this->loadLayout('layout-simple');
        $this->render('photo');
        //$this->_redirect('/user/manage');
    }
    
}
