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
 * @package    Common_Db_Table
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Abstract.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Commonp_Db_Table_Abstract
 *
 * @category   Common
 * @package    Common_Db_Table
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
abstract class Common_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    //protected $_name    = 'table';
    //protected $_primary = 'id';    
    
    //protected $_cols = array();       


    public function insert(array $data)
    {
        $cols = $this->_getCols();
        
        foreach ($cols as $key) {
            if (!isset($data[$key])) {
                unset($data[$key]);
            }
        }
    
        if (in_array('created', $cols) && empty($data['created'])) {
            $data['created'] = date('Y-m-d H:i:s');
        }
        if (in_array('updated', $cols) && empty($data['updated'])) {
            $data['updated'] = date('Y-m-d H:i:s');
        }
        
        return parent::insert($data);
    }
    
    public function delete($where)
    {
        return parent::delete($where);
    }

    public function update(array $data, $where = array())
    {
        $cols = $this->_getCols();
        
        foreach ($cols as $key) {
            if (!isset($data[$key])) {
                unset($data[$key]);
            }
        }
        
        if (isset($data[$this->_primary])) {
            unset($data['uid']);
        }
            
        if (in_array('updated', $cols) && empty($data['updated'])) {
            $data['updated'] = date('Y-m-d H:i:s');
        }
        
        return parent::update($data, $where);
    }
    
    public function getById($id)
    {
        $db = $this->getAdapter();
        $select = $db->select();

        $select->from($this->_name, '*');
        $select->where($this->_primary.'= ?', $id);

        try {
            $rs = $db->fetchRow($select);
        } catch (Exception $e) {
            throw $e;
        }

        return $rs;
    }
    
    public function getList(array $where, $order = array(), $limit = 10, $offset = 0)
    {
        $db = $this->getAdapter();
        $select = $db->select();

        $cols = $this->_getCols();
        
        if (in_array('isdelete', $cols) && !isset($where['isdelete'])) {
            $where['isdelete'] = 0;
        }

        $select->from($this->_name, '*');
        
        Common_Db_Util::buildSelectWhere($select, $where);
        
        $select->order($order);
        $select->limit($limit, $offset);
        
        try {
            $rs = $db->fetchAll($select);
        } catch (Exception $e) {
            throw $e;
        }

        return $rs;
    }
    
    public function getCount($where)
    {
        $db = $this->getAdapter();
        $select = $db->select();
        
        $cols = $this->_getCols();
        
        if (in_array('isdelete', $cols) && !isset($where['isdelete'])) {
            $where['isdelete'] = 0;
        }

        $select->from($this->_name, 'count(*) as count');
        
        Common_Db_Util::buildSelectWhere($select, $where);
        
        $select->order($order);
        $select->limit($limit, $offset);
        
        try {
            $rs = $db->fetchRow($select);
        } catch (Exception $e) {
            throw $e;
        }

        return (int)$rs['count'];        
    }
}
