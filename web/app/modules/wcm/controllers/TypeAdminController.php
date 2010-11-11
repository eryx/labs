<?php
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

        

class Wcm_TypeAdminController extends Common_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->loadLayout('layout/simple');
    }
    
    public function indexAction()
    {
        $_e = new Common_Data_Entry_Type();   
        $this->view->items = $_e->getList(array(), array('updated DESC'), 512);
    }
    
    public function createAction()
    {
    }
    
    public function createpostAction()
    {
        $_e = new Common_Data_Entry_Type();
        
        $item = array('typeid' => $this->_params['typeid'],
            'title' => $this->_params['title'],
            'summary' => $this->_params['summary']);

        try {
            $_e->insert($item);
        } catch (Exception $e) {
            throw $e;
        }
        
        $links = array(
            array('title' => 'Manage Fields',
                'url' => '/wcm/type-admin/fields?typeid='.$item['typeid']),
            array('title' => 'Content Types',
                'url' => '/wcm/type-admin/')
        );
        
        $this->view->message = Common_Message::get('success', 'Success', $links);
        
        $this->render('message-block', null, true);
    }
    
    public function fieldsAction()
    {
        $_fc = new Common_Data_Field_Config();
        
        $where = array('entity_type' => $this->_params['typeid']);
        $this->view->items = $_fc->getList($where, array(), 128);
        
        $this->view->typeid = $this->_params['typeid'];
        
        $this->view->types = Common_Data_Field_Type::getTypes();
        
        $this->render('fields');
    }
    
    public function fieldcreateAction()
    {
        $conn = Common_Db_Doctrine::getConnection(Common_Config::get('common')->database->master->dsn);
        
        $field_table = 'field_data_'.$this->_params['typeid'];
        $table_column  = 'field_'.$this->_params['field_name'];
        
        $_fc = new Common_Data_Field_Config();
        
        $conf = array('entity_type' => $this->_params['typeid'],
            'field_name' => $this->_params['field_name']);
            
        $count = $_fc->getCount($conf);
        
        if ($count == 0) {
            $conf['field_type'] = $this->_params['field_type'];
            $conf['field_label'] = $this->_params['field_label'];
            $conf['storage_type'] = $field_table;
            $_fc->insert($conf);
        }
        
        try {
        
            if (!$conn->import->tableExists($field_table)) {
        
                $cols = array(
                    'entityid' => array('type' => 'string', 'length' => 36, 'primary' => true),
                    "$table_column" => array('type' => 'string')
                );
            
                $sql = $conn->export->createTableSql($field_table, $cols); 
                $_fc->getAdapter()->query($sql[0]);
                
            } else {
                
                $cols = $conn->import->listTableColumns($field_table);
            
                if (!isset($cols[$table_column])) {
            
                    $alter = array(
                        "add" => array(
                            "$table_column" => array('type' => 'string'),           
                        )
                    );
            
                    $sql = $conn->export->alterTableSql($field_table, $alter);
                    $_fc->getAdapter()->query($sql);
                }
            }
            
        } catch (Exception $e) {
        
        } catch (PDOException $e) {
        
        }
        
        
        return $this->fieldsAction();
        
    }
}
