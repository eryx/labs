<?php
defined('SYS_ENTRY') or die('Access Denied!');


class Common_Data_Db
{
    //
	public static $entry = array('name' => 'entry', 'primary' => 'entryid');
	public static $entry_type = array('name' => 'entry_type', 'primary' => 'typeid');

	//
	public static $field_config = array('name' => 'field_config',
	    'primary' => array('entity_type', 'field_name'));
	public static $field_data = array('name' => 'field_data', 
		'primary' => array('fieldid', 'entryid'));

    //
    public static $taxonomy_index = array('name' => 'taxonomy_index',
        'primary' => array('entityid', 'tid'));
    public static $taxonomy_term_data = array('name' => 'taxonomy_term_data',
        'primary' => array('tid', 'vid'));

    //
    public static function getFieldData($name)
    {
        return array('name' => "$name", 'primary' => 'entityid');
    }
}
