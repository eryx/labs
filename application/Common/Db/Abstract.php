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
 * @package    Common_Db
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Abstract.php 802 2010-01-28 16:06:41Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Common_Db_Abstract
 *
 * @category   Common
 * @package    Common_Db
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
abstract class Common_Db_Abstract
{
    protected $_tdb = null;

    public function insert(array $data)
    {        
        try {
            $rs = $this->_tdb->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
        
        return $rs;
    }

    public function delete($id)
    {        
        $where = $this->_tdb->getAdapter()
            ->quoteInto($this->_tdb->primary.' = ?', $id);
        
        try {
            $rs = $this->_tdb->delete($where);
        } catch (Exception $e) {
            throw $e;
        }
        
        return $rs;
    }
    
    public function update(array $data)
    {
        if (!isset($data[$this->_tdb->primary])) {
            $where = $this->_tdb->getAdapter()
                ->quoteInto($this->_tdb->primary.' = ?', $data[$this->_tdb->primary]);
        } else {
            throw new Exception($this->_tdb->primary.' can not be null');
        }
        
        try {
            $rs = $this->_tdb->update($data, $where);
        } catch (Exception $e) {
            throw $e;
        }

        return $rs;
    }
    
    public function get($id)
    {
        $db = $this->_tdb->getAdapter();
        $select = $db->select();

        $select->from($this->_tdb->name, '*');
        $select->where($this->_tdb->primary.'= ?', $id);

        try {
            $rs = $db->fetchRow($select);
        } catch (Exception $e) {
            throw $e;
        }

        return $rs;
    }
    
    public function getList(array $where, $order = array(), $limit = 10, $offset = 0)
    {
        $db = $this->_tdb->getAdapter();
        $select = $db->select();
        
        if (in_array('isdelete', $this->_tdb->_cols) && !isset($where['isdelete'])) {
            $where['isdelete'] = 0;
        }

        $select->from($this->_tdb->name, '*');
        
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
        $db = $this->_tdb->getAdapter();
        $select = $db->select();
        
        if (in_array('isdelete', $this->_tdb->_cols) && !isset($where['isdelete'])) {
            $where['isdelete'] = 0;
        }

        $select->from($this->_tdb->name, 'count(*) as count');
        
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
