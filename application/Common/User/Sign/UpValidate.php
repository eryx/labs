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
 * @package    Common_User
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: UpValidate.php 834 2010-03-22 16:26:33Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Common_User_Sign_Validate
 *
 * @category   Common
 * @package    Common_User
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Common_User_Sign_UpValidate
{
    /**
     * Parameters check for user-sign-in action 
     *
     * @param array $params
     * @param string $msg Error message when false
     * @return bool
     */
    public function isValid(&$params, &$msg = null) 
    {
        if (!isset($params['uname'])) {
            $msg = 'Username can not be null';
            return false;
        }
        
        $params['uname'] = strtolower(trim($params['uname']));
        
		if (!ereg('^[a-z]{1,1}[a-z0-9]{2,15}$', $params['uname'])) {
		    $msg = 'Invalid Username';
            return false;
		}
		
		if (!Zend_Validate::is($params['pass'], 'StringLength', array(6, 32))) {
            $msg = 'Password must between 6 and 32 characters long';
            return false;
        }
        
        if ($params['pass'] != $params['repass']) {
		    $msg = 'Passwords do not match';
            return false;
		}

		if (!Zend_Validate::is($params['email'], 'EmailAddress')) {
            $msg = 'This is not a valid email address';
            return false;
        }
        
        return true;
    }
}
