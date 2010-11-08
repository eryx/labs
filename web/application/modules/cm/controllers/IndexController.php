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
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Cm_IndexController
 *
 * @category   Cm
 * @package    Cm_IndexController
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Cm_IndexController extends Common_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function indexAction()
    {
        $type = $this->_request->getParam('type');
        
        $cfg = Common_Config::get("cm/$type.ini")->index->index;
        
        if (isset($cfg->session)) {
            $this->view->session = Common_Session::getInstance();
        }
        
        foreach ($cfg->datax as $k => $v) {
            
            $items = Cm_Model_Datax::getFeed($v->query);
            $block = APPLICATION_PATH.'/modules/cm/views/blocks/'.$v->block;
            
            $out = Cm_Model_Datax::getBlock($block, $items);
            
            $this->view->{'datax_'.$k} = $out;
        }

        $this->render('layout/simple', null, true);
    }
}
