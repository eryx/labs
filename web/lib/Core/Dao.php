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
 * @category   Core
 * @package    Core_Dao
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */


/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');


/**
 * Class Core_Dao
 *
 * @category   Core
 * @package    Core_Dao
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Core_Dao
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
    
    /** */
    protected static $objs = array();
    
    /** */    
    public static function factory($conf) 
    {   
        if (!isset($conf['name'])) {
            return false;
        }
        
        if (!isset($conf['primary'])) {
            $conf['primary'] = 'id';
        }
        
        if (!isset(self::$objs[$conf['name']]) || self::$objs[$conf['name']] === NULL) {
            self::$objs[$conf['name']] = new Core_DaoTable($conf);
        }
        
        return self::$objs[$conf['name']];
    }
    
    /**
     * Convert array to Zend_Db_Select
     *
     * @param   Zend_Db_Select   $select
     * @param   array   $where
     * @return  void
     */  
    public static function buildSelectWhere(Zend_Db_Select &$select, $where)
    {
        if (!is_array($where) || count($where) == 0) {
            return;
        }
        
        Core_Dao::buildArrayWhere($where);
        
        foreach ($where as $k => $v) {
            $select->where($k, $v);
        }
    }
    
    /**
     * Convert array to Zend_Db_Select(Array)
     *
     * @param   array   $where
     * @return  void
     */  
    public static function buildArrayWhere(&$where)
    {
        if (!is_array($where) || count($where) == 0) {
            return;
        }

        foreach ((array)$where as $key => $value) {
            
            if (preg_match('/(.*)\.(.*)\.(.*)/', $key, $reg)) {
                $cpe = $reg['1'];
                $tab = $reg['2'].'.';
                $col = $reg['3'];
            } elseif (preg_match('/(.*)\.(.*)/', $key, $reg)) {
                $cpe = $reg['1'];
                $tab = '';
                $col = $reg['2'];
            } else {
                $cpe = 'e';
                $tab = '';
                $col = $key;
            }
            
            switch ($cpe) {
                case 'e':
                    $cpe = ' = ? ';
                    break;
                case 'gt':
                    $cpe = ' > ? ';
                    break;
                case 'ge':
                    $cpe = ' >= ? ';
                    break;
                case 'lt':
                    $cpe = ' < ? ';
                    break;
                case 'le':
                    $cpe = ' <= ? ';
                    break;
                case 'like':
                    $cpe = ' LIKE ? ';
                    break;
                case 'in':
                    $cpe = ' IN (?) ';
                    break;
                case 'notin':
                    $cpe = ' NOT IN (?) ';
                    break;
                case 'ne':
                    $cpe = ' != ? ';
                    break;
                default :
                    $cpe = ' = ? ';
            }

            unset($where[$key]);
            $where[$tab.$col.$cpe] = $value;                        
        }
        
    }
}

/**
 * Class Core_DaoTable
 *
 * @category   Core
 * @package    Core_DaoTable
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Core_DaoTable extends Zend_Db_Table_Abstract
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
        
        if (is_string($this->_primary)) {
            if (isset($data[$this->_primary])) {
                unset($data[$this->_primary]);
            }
        } else if (is_array($this->_primary)) {
            foreach ($this->_primary as $v) {
                if (isset($data[$v])) {
                    unset($data[$v]);
                }
            }
        }
        
        if (count($data) == 0) {
            return;
        }
            
        if (in_array('updated', $cols) && empty($data['updated'])) {
            $data['updated'] = date('Y-m-d H:i:s');
        }
        
        foreach ($where as $k => $v) {
            $where[$k.' = ?'] = $v;
            unset($where[$k]);
        }
        //Core_Dao::buildArrayWhere($where);
        
        return parent::update($data, $where);
    }
    
    public function getById($id)
    {
        $db = $this->getAdapter();
        $select = $db->select();

        $select->from($this->_name, '*');
        
        // TODO array-to-array
        if (is_string($this->_primary)) {
            $select->where($this->_primary.'= ?', $id);
        } else if (is_array($this->_primary)) {
            foreach ($this->_primary as $v) {
                $select->where($v.'= ?', $id);
            }
        }        

        try {
            $rs = $db->fetchRow($select);
        } catch (Exception $e) {
            throw $e;
        }

        return $rs;
    }
    
    public function getList($where = array(), $order = array(), $limit = 10, $offset = 0)
    {
        $db = $this->getAdapter();
        $select = $db->select();

        $cols = $this->_getCols();

        $select->from($this->_name, '*');
        
        Core_Dao::buildSelectWhere($select, $where);
        
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

        $select->from($this->_name, 'count(*) as count');
        
        Core_Dao::buildSelectWhere($select, $where);
        
        try {
            $rs = $db->fetchRow($select);
        } catch (Exception $e) {
            throw $e;
        }

        return (int)$rs['count'];         
    }
}
