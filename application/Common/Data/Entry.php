<?php
defined('SYS_ENTRY') or die('Access Denied!');


class Common_Data_Entry
{
	public function getEntry($id)
	{
	
		try {

			$_entry = Common_Db::getInstance(Common_Data_Db::$entry);
	        $entry = $_entry->getById($id);

            $_field = new Common_Data_Field();
            $fields = $_field->getFields($id, $entry['type']);
            $entry = array_merge($entry, $fields);

	    } catch (Exception $e) {
	    	throw $e;
	    }
	    
	    return $entry;
	}

    public function getList()
    {
    }

    public function deleteById()
    {
    }
}
