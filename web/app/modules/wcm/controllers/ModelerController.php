<?php
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

class Wcm_ModelerController extends Common_Controller_Action
{ 
    public function indexAction()
    {
        $this->loadLayout('layout/simple');
    }
    
    public function blockAction()
    {
        $this->render($this->_params['vpath'], null, true);
    }
}
