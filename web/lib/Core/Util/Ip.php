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
 * @package    Core_Util
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Ip.php 799 2010-01-24 15:33:43Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class Core_Util_Ip
 *
 * @category   Core
 * @package    Core_Util
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Core_Util_Ip
{
    /**
     * Get remote address/client ip  
     *
     * @return string
     */
    public static function getRemoteAddr()
    {
        $ip = false;
        
        if (strlen(@$_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $tmp_ip = explode(',', $addr);
            $ip = $tmp_ip[0];
        }
        
        return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
}
