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
 * @category   Core_Boot
 * @package    Core_Boot
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Core_Boot.php 856 2010-05-07 16:05:39Z evorui $
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

require_once "Core/Common.php";

function __autoload($class) {
    $class = str_replace('_', '/', $class);
    if (preg_match("#^(.*)/Model/(.*)#i", $class, $regs)) {
        $class = strtolower($regs[1]."/models/").$regs[2];
    }
    require_once ($class .".php");
}
//spl_autoload_register('__autoload');

try {
    
    $reqs = new Core_Request();
    if (isset($config['routes'])) {
        $reqs->router($config['routes']);
    }

    $ctr = str_replace(array('-', '.'), ' ', $reqs->ctr);
    $ctr = str_replace(' ', '', ucwords($ctr)).'Controller';
    $pat = SYS_ROOT ."app/{$reqs->mod}/controllers/";
    
    if (file_exists($pat."{$ctr}.php")) {
        require_once $pat."{$ctr}.php";
    } else if (file_exists($pat."IndexController.php")) {
        require_once $pat."IndexController.php";
        $ctr = "IndexController";
    } else {
        throw new Exception('Invalid Request');
    }
    
    $controller = new $ctr($reqs);           
    $controller->dispatch();
     
} catch (Exception $e) {
    echo $e->getMessage();
}
