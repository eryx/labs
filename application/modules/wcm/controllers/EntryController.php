<?php
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

class Wcm_EntryController extends Common_Controller_Action
{ 
    public function indexAction()
    {
    }

    public function viewAction()
    {
        $_entry = new Common_Data();
        $id = '49fb2164-969a-08f4-d5f3-3ffa96a4d91a';
        $entry = $_entry->getEntry($id);
        print_r($entry);
        
//        $this->loadLayout('layout/simple');
//        $this->render('entry/view', null, true);
        $this->render('layout/simple', null, true);
    }

    public function createAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}
