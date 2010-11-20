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
 * @category   User
 * @package    User_Model
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Validate.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class User_Model_Sign_Validate
 *
 * @category   User
 * @package    User_Model
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class User_Model_Sign_Validate
{
    /**
     * Parameters check for user-sign-in action 
     *
     * @param array $params
     * @param string $msg Error message when false
     * @return bool
     */
    public static function isValid(&$params, &$msg = null) 
    {
        $params['uname'] = strtolower(trim($params['uname']));
        
        if (! isset($params['persistent']) || $params['persistent'] != 1) {
            $params['persistent'] = 0;
        }
        
		if (!preg_match('/^[a-z]{1,1}[a-z0-9]{2,15}$/', $params['uname'])) {
		    $msg = 'Invalid Username';
            return false;
		}
        
        return true;
    }
}
