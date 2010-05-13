<?php
defined('SYS_ENTRY') or die('Access Denied!');


class User_Model_Profile
{
    protected $_cols = array('userid', 'name', 'birthday', 'gender', 'desc',
        'address');
    
    public function update($params, $where) 
    {
        foreach ($params as $key => $value) {
            if (!in_array($key, $this->_cols)) {
                unset($params[$key]);
            }
        }

        try {
            $_user = Common_Db::get('User_Model_Db_UserProfile');
            $_user->update($params, $where);
        } catch (Exception $e) {
            throw $e;
        }
    } 
}
