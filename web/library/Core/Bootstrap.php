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
 * @category   Core_Bootstrap
 * @package    Core_Bootstrap
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Core_Bootstrap.php 856 2010-05-07 16:05:39Z evorui $
 */

/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');


// Don't escape quotes when reading files from the database, disk, etc.
ini_set('magic_quotes_runtime', '0');

// Use session cookies, not transparent sessions that puts the session id in
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');
// Don't send HTTP headers using PHP's session handler.
ini_set('session.cache_limiter', 'none');
// Use httponly session cookies.
ini_set('session.cookie_httponly', '1');

// Set sane locale settings
// to ensure consistent string, dates, times and numbers handling.
setlocale(LC_ALL, 'en_US.utf-8');

  
if (ini_get('magic_quotes_gpc')) {

	function array_stripslashes(&$v) {
		$v = stripslashes($v);
	}
	
	function array_stripslashes_files(&$v, $k) {
	    if ($k != 'tmp_name') {
		    $v = stripslashes($v);
		}
	}

	array_walk_recursive($_GET, 'array_stripslashes');
	array_walk_recursive($_POST, 'array_stripslashes');
	array_walk_recursive($_COOKIE, 'array_stripslashes');
	array_walk_recursive($_REQUEST, 'array_stripslashes');
	array_walk_recursive($_FILES, 'array_stripslashes_files');
}


class load
{
	public static function view($file = NULL, $vars = NULL)
	{
		if (is_array($vars)) {
			foreach ($vars as $key => $value) {
				$$key = $value;
			}
		}
        unset($vars);

		ob_start();

		include($file);

		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
}

// config database session cache view routes hooks

require_once SYS_ROOT."application/cm/controllers/IndexController.php";

$controller = new IndexController();
$controller->indexAction();
$controller->render();




