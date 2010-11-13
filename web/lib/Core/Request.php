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
 * @category   Core_Request
 * @package    Core_Request
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */

/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');



class Core_Request
{
    public $uri     = '';
    
    public $mod     = 'cm';
    public $ctr     = 'error';
    public $act     = 'index';
    
    public function __construct()
    {
        if (isset($_SERVER['REDIRECT_URL'])) {
            $this->uri = trim($_SERVER['REDIRECT_URL'], '/');
        }
    }
    
    public function __set($key, $val)
    {
        if ('_' != substr($key, 0, 1)) {
            $this->$key = $val;
        }
    }
    
    public function router($routes = array())
    {
        $uri = $this->uri == '' ? array() : explode('/', $this->uri);
        $urc = count($uri);

        /* $routes[] = array('route' => ':mod/:ctr/:act',
            'mod' => 'cm', 'ctr' => 'index', 'act' => 'index'); */
        
        foreach ($routes as $v) {
            
            $rot = explode('/', trim($v['_route'], '/'));
            $max = max($urc, count($rot));

            $pre = NULL;

            for ($i = 0; $i < $max; $i++) {                
                
                if (isset($rot[$i]) && isset($uri[$i])) {
                
                    if (substr($rot[$i], 0, 1) == ":") {
                        $v[substr($rot[$i], 1)] = $uri[$i];
                    } else if ($rot[$i] != $uri[$i]) {
                        continue 2;
                    }
                
                } else if (isset($uri[$i])) {

                    if ($pre === NULL) {
                        $pre = $uri[$i];
                    } else {
                        $v[$pre] = $uri[$i];
                        $pre = NULL;       
                    }
                
                }
            }
            
            if (isset($v['mod']) && isset($v['ctr']) && isset($v['act'])) {
                foreach ($v as $key => $val) {
                    $this->$key = $val; // TODO XSS
                }
                break;
            }
        }
    }
}
