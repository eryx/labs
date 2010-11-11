<?php
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

        

class Wcm_LayoutAdminController extends Common_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->loadLayout('layout/simple');
    }
    
    public function indexAction()
    {
        $this->view->typeid = $this->_params['typeid'];
        $this->view->name = $this->_params['name'];
        
        $_e = new Common_Data_Entry_Type();   
        $this->view->items = $_e->getList(array(), array('updated DESC'), 512);
    }
    
    public function editAction()
    {
        $this->view->typeid = $this->_params['typeid'];
        $this->view->name = $this->_params['name'];        
        
        $source = file_get_contents($this->view->getScriptPath('layout/entry-index.phtml'));
        $this->view->source = str_replace('<', '&lt;', $source);
        
        $this->render('edit');
    }
    
    public function updateAction()
    {
        return $this->editAction();
    }
}
