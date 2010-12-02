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
 * @version    $Id: ProfileController.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class ProfileController
 *
 * @category   User
 * @package    Controller
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class ProfileController extends Core_Controller
{   
    public function indexAction()
    {   
        $pf = NULL;
        
        if (preg_match('#^(.+)/profile/(.+)$#i', $this->reqs->uri, $regs)) {
            
            $uname = $regs[2];
        
            try {
        
                $this->initdb();
                $_profile = Core_Dao::factory(array('name' => 'user_profile'));
            
                $user = $_profile->getList(array('uname' => $uname));
                if (isset($user[0]['id'])) {
                    $des = str_split($uname);            
                    $path = '/data/user/'.$des['0'].'/'.$des['1'].'/'.$des['2'].'/'.$uname;
    
                    if (!file_exists(SYS_ROOT.$path."/w100.png")) {
                        $path = '/data/user';
                    }
        
                    $pf = $user[0];
                    $pf['photo_path'] = $path;
                    $pf['content'] = Core_Util_Format::richEditFilter($pf['content']);
                }
            
            } catch (Exception $e) {
                //
            }
        }
        
        if ($pf === NULL) {
            $this->view->message = Core_Message::get('error', 'Profile not found');
        } else {
            $this->view->profile = $pf;
            $this->view->content = $this->view->render('profile/index');
            unset($pf, $this->view->profile);
        }
        
        $this->response('layout');
    }
    
    public function avatarAction()
    {
        if (preg_match('#^(.+)/profile/avatar/(.+)-(.+).png$#i', $this->reqs->uri, $regs)) {   
            $uname = $regs[2];
            $wsize = $regs[3];
        } else {
            $uname = "guest";
            $wsize = "w100";
        }
        
        if (!in_array($wsize, array("w100", "w40"))) {
            $wsize = "w100";
        }
        
        $des = str_split($uname);
        $path = '/data/user/'.$des['0'].'/'.$des['1'].'/'.$des['2'].'/'.$uname;
    
        if (!file_exists(SYS_ROOT.$path."/$wsize.png")) {
            $file = SYS_ROOT."/data/user/$wsize.png";
        } else {
            $file = SYS_ROOT.$path."/$wsize.png";
        }

        $media = array('name' => $uname,
            'media_name'=> $uname.'-'.$wsize.'.png',
            'imagePath' => $file);

        $this->_output($media);       
        exit;
    }
    
    private function _output($media)
    {
        //ob_end_clean();
        $ims = getimagesize($media['imagePath']);

        //header("Cache-Control: private");
        header("Pragma: cache");
        header("Expires: " . gmdate("D, d M Y H:i:s",time() + 36000) . " GMT");
        //header("Last-Modified: " . gmdate("D, d M Y H:i:s",strtotime($media['modified'])) . " GMT");
        header("Content-type: ".$ims['mime']);

        /*$validator = new Zend_Validate_File_IsImage();
        if ($validator->isValid($media['imagePath'], array('type' => $ims['mime']))) {
            header("Content-Disposition: inline; filename=".$media['media_name']);
        } else {
            header('Content-Disposition: attachment; filename='.$media['media_name']);
        }*/
        header("Content-Disposition: inline; filename=".$media['media_name']);
        header("Content-Length: ".filesize($media['imagePath']));    

        $fp = fopen($media['imagePath'], "rb"); 

        fpassthru($fp);
        fclose($fp);

        exit;        
    }
}
