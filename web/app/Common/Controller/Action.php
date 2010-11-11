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
 * @category   Common
 * @package    Controller
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Action.php 829 2010-03-18 14:50:56Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Common_Controller_Action
 *
 * @category   Common
 * @package    Controller
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Common_Controller_Action extends Zend_Controller_Action
{

    protected $_params;
    
    protected $_session;
    
    protected $_requset;
    
    /**
     * Initialize entry
     *
     * @return void
     */
    public function init()
    {
        $this->_requset = $this->getRequest();
        $this->_params = $this->getRequest()->getParams();
        
        $this->_session = Common_Session::getInstance();
        
        $this->_initView();
      
    }    
    
    /**
     * Initialize the view's paths
     *
     * @return void
     */   
    protected function _initView()
    {
        $this->view->session = $this->_session;
        
        //print_r($this->view);
        $this->view->setScriptPath(array());

        $module = $this->getRequest()->getParam('module');
        $basePath = $this->getRequest()->getBasePath();
        
        $this->view->basePath = $basePath;
        
        $this->view->baseModulePath = $basePath."/$module";
        
        /**
         * Setting the view's default path
         */
        $this->view->addBasePath(APPLICATION_PATH.'/modules/default/views');
        $this->view->viewDefaultPath = $basePath.'/_default';
        
        /**
         * Setting the view's module path
         */
        if ($module != 'default') {
            $this->view->addBasePath(APPLICATION_PATH."/modules/$module/views");
            $this->view->viewModulePath = $basePath."/_$module";
        }

    }
    
    /**
     * Load layout view
     *
     * @param string $layout
     * @return void
     */
    public function loadLayout($layout)
    {
        Zend_Layout::startMvc(array('layout' => $layout)); 
    }
    
 
}
