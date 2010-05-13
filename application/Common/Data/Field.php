<?php
defined('SYS_ENTRY') or die('Access Denied!');


class Common_Data_Field
{
    public function getFields($entry)
    {
        $fields = array();

        $entityid = $entry['entryid'];
        $type = $entry['type'];
        
        $_conf = Common_Db::getInstance(Common_Data_Db::$field_config);
        $insts = $_conf->getList(array('entity_type' => $type));

        // Get Fields Data
        $regex = '#^field_(.+)$#i';
        $storage = array();
        foreach ($insts as $v) {

            if (!isset($storage[$v['storage_type']])) {
            
                $_db_cfg = Common_Data_Db::getFieldData($v['storage_type']);
                $_data = Common_Db::getInstance($_db_cfg);
                    
                $ret = $_data->getById($entityid);
                //$terms = array();
                //foreach ($ret as $v2) {
                //    $terms[] = $v2['field_tid'];
               // }
                
                $storage[$v['storage_type']] = $ret;
            }

            switch ($v['field_type']) {

                case Common_Data_Field_Type::TERM_AUTO:

                    /* if (count($terms) > 0) {
                        $_data = Common_Db::getInstance(Common_Data_Db::$taxonomy_term_data);
                        $ret = $_data->getList(array('in.tid' => $terms), array(), 100);
                        foreach ($ret as $v2) {
                            $fields['field:term'][] = array('tid' => $v2['tid'],
                                'name' => $v2['name']);
                        }
                    }*/

                    if (isset($storage[$v['storage_type']]['field_'.$v['field_name']])) {
                        $fields['field:'.$v['field_name']] = $storage[$v['storage_type']]['field_'.$v['field_name']];
                    } else {
                        $fields['field:'.$v['field_name']] = '';
                    }
                    break;
                
                case Common_Data_Field_Type::TEXT_SUMMARY:

                    if (isset($storage[$v['storage_type']]['field_'.$v['field_name']])) {
                        $fields['field:'.$v['field_name']] = $storage[$v['storage_type']]['field_'.$v['field_name']];
                    } else {
                        $fields['field:'.$v['field_name']] = '';
                    }
                    
                    if (isset($storage[$v['storage_type']]['field_'.$v['field_name'].'_summary'])) {
                        $fields['field:'.$v['field_name'].'_summary'] = $storage[$v['storage_type']]['field_'.$v['field_name'].'_summary'];
                    } else {
                        $fields['field:'.$v['field_name'].'_summary'] = '';
                    }

                    /* foreach ($ret as $k2 => $v2) {
                        if (preg_match($regex, $k2, $v3)) {
                            $fields['field:'.$v3[1]] = $v2;
                        }
                    }*/
                    
                    break;
                default:
                    //...
            }
        }

        return $fields;	        
    }
}
