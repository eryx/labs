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
 * @version    $Id: Util.php 799 2010-01-24 15:33:43Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Common_Db_Util
 *
 * @category   Common
 * @package    Common_Db
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Common_Db_Util
{   
    public static function buildSelectWhere(&$select, $where)
    {

        foreach ((array)$where as $key => $value) {
            
            if (ereg('(.*)\.(.*)\.(.*)', $key, $reg)) {
                $operator = $reg['1'];
                $table    = $reg['2'].'.';
                $column   = $reg['3'];
            } elseif (ereg('(.*)\.(.*)', $key, $reg)) {
                $operator = $reg['1'];
                $table    = '';
                $column   = $reg['2'];
            } else {
                $operator = 'e';
                $table    = '';
                $column   = $key;
            }
            
            switch ($operator) {
                case 'e':
                    $operator = ' = ? ';
                    break;
                case 'gt':
                    $operator = ' > ? ';
                    break;
                case 'ge':
                    $operator = ' >= ? ';
                    break;
                case 'lt':
                    $operator = ' < ? ';
                    break;
                case 'le':
                    $operator = ' <= ? ';
                    break;
                case 'like':
                    $operator = ' LIKE ? ';
                    break;
                case 'in':
                    $operator = ' IN (?) ';
                    break;
                case 'notin':
                    $operator = ' NOT IN (?) ';
                    break;
                default :
                    $operator = ' = ? ';
            }

            $select->where($table.$column.$operator, $value);
                        
        }
        
    }
}
