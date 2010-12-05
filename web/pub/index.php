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
 * @category   Index
 * @package    Index
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: index.php 856 2010-05-07 16:05:39Z evorui $
 */

define('START_TIME', microtime(true));
define('START_MEMORY_USAGE', memory_get_usage());

define('DS', DIRECTORY_SEPARATOR);
define('SYS_ROOT', realpath('..'). DS);


set_include_path(implode(PATH_SEPARATOR, 
    array(SYS_ROOT.'lib', SYS_ROOT.'app', get_include_path())));


require SYS_ROOT.'conf/config.inc.php';
require 'Core/Boot.php';

echo "<script> document.getElementById('htdebug').innerText = '"
    . round((microtime(true) - START_TIME), 3) * 1000 ." ms, "
    . round((memory_get_usage() - START_MEMORY_USAGE) / 1024, 0) ." KB'; </script>";

