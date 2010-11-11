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
 * @package    Common_Session
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Session.php 799 2010-01-24 15:33:43Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Common_Session
 *
 * @category   Common
 * @package    Common_Session
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Common_Session
{    
    public static function getInstance()
    {
        return Common_Session_Instance::getInstance();
    }
}
