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
 * @version    $Id: Table.php 839 2010-03-28 10:27:39Z onerui $
 */


/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');


/**
 * Class Common_Db_Table
 *
 * @category   Common
 * @package    Common_Db_Table
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Common_Db_Table extends Zend_Db_Table_Abstract
{
    /**
     * The table name.
     *
     * @var array
     */
    protected $_name = 'table';
    
    /**
     * The primary key column or columns.
     * A compound key should be declared as an array.
     * You may declare a single-column primary key
     * as a string.
     *
     * @var mixed
     */
    protected $_primary = 'id';
    
    /**
     * Inserts a new row.
     *
     * Append the created/modified time automatically
     *
     * @param  array  $data  Column-value pairs.
     * @return mixed         The primary key of the row inserted.
     */
    public function insert(array $data)
    {
        $cols = $this->_getCols();

        foreach ($data as $k => $v) {
            if (!in_array($k, $cols)) {
                unset($data[$k]);
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
        if (count($where) == 0) {
            throw new Exception();
        }
        foreach ($where as $k => $v) {
            $where[$k.' = ?'] = $v;
            unset($where[$k]);
        }
        return parent::delete($where);
    }

    /**
     * Updates existing rows.
     *
     * Append the modified time automatically
     *
     * @param  array        $data  Column-value pairs.
     * @param  array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
     * @return int          The number of rows updated.
     */
    public function update(array $data, $where = array())
    {
        if (count($where) == 0) {
            throw new Exception();
        }
        
        $cols = $this->_getCols();

        foreach ($data as $k => $v) {
            if (!in_array($k, $cols)) {
                unset($data[$k]);
            }
        }
        
        if (isset($data[$this->_primary])) {
            unset($data[$this->_primary]);
        }
            
        if (in_array('updated', $cols) && empty($data['updated'])) {
            $data['updated'] = date('Y-m-d H:i:s');
        }
        
        foreach ($where as $k => $v) {
            $where[$k.' = ?'] = $v;
            unset($where[$k]);
        }
        
        return parent::update($data, $where);
    }
    
    public function getById($id)
    {
        try {
            $rs = $this->find($id)->toArray();
            if (isset($rs[0]) && count($rs[0]) > 0) {
            	$rs = $rs[0];
            } else {
            	$rs = array();
            }
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
        
        if (in_array('deleted', $cols) && !isset($where['deleted'])) {
            $where['deleted'] = 0;
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
        
        if (in_array('deleted', $cols) && !isset($where['deleted'])) {
            $where['deleted'] = 0;
        }

        $select->from($this->_name, 'count(*) as count');
        
        Common_Db_Util::buildSelectWhere($select, $where);
        
        try {
            $rs = $db->fetchRow($select);
        } catch (Exception $e) {
            throw $e;
        }

        return (int)$rs['count'];         
    }
}
