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
 * @version    $Id: ProfileController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class User_ProfileController
 *
 * @category   User
 * @package    User_Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class User_ProfileController extends Common_Controller_Action
{
    public function init()
    {
        parent::init();

        if (!isset($this->_params['user'])) {
            throw new Exception('Page Not Found');
        }
    }
    
    public function indexAction()
    {
        $_user = new User_Model_Db_User();
        $_profile = new User_Model_Db_UserProfile();
        
        try {
            $user = $_user->getList(array('uname' => $this->_params['user']));
            if (isset($user[0]['uid'])) {
                $pf = $_profile->getById($user[0]['uid']);
                $this->view->user = $user[0];
            } else {
                throw new Exception('Page Not Found');
            }
            $pf['desc'] = Common_Util_Format::richEditFilter($pf['desc']);
        } catch (Exception $e) {
            throw new Exception('Page Not Found');
        }
        
        $this->view->profile = $pf;
        $this->loadLayout('layout-simple');
    }
   
    public function photoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        
        $uri = $this->getRequest()->getRequestUri();
        
        $regex = '#^(.+)/photo/(.+).png$#i';
        
        if (preg_match($regex, $uri, $v)) {
            $de = str_split($this->_params['user']);            
            $file = SYS_ENTRY.'/data/user/'.$de[0].'/'.$de[1].'/'.$de[2];
            $file.= '/'.$this->_params['user'].'/'.$v[2].'.png';
            if (!file_exists($file)) {
                $file = SYS_ENTRY.'/data/user/'.$v[2].'.png';
                if (!file_exists($file)) {
                    die();
                }
            }
        } else {
            die();
        }     
        
        ob_end_clean();
        $ims = getimagesize($file);

        header("Cache-Control: private");
        header("Pragma: cache");
        header("Expires: " . gmdate("D, d M Y H:i:s",time() + 31536000). " GMT");
        header("Content-type: image/png");

        $validator = new Zend_Validate_File_IsImage();
        if ($validator->isValid($file, array('type' => 'image/png'))) {
            header("Content-Disposition: inline; filename=photo");
        } else {
            header('Content-Disposition: attachment; filename=photo');
        }
        header("Content-Length: ".filesize($file));    

        $fp = fopen($file, "rb"); 
        
        fpassthru($fp);
        fclose($fp);
   
        die();
    }
}
