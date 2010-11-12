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
        $uris = explode('/', $this->uri);
        
        $routes[] = array('route' => ':mod/:ctr/:act',
            'mod' => 'cm', 'ctr' => 'index', 'act' => 'index');
        
        foreach ($routes as $val) {
            
            $route = explode('/', trim($val['route'], '/'));
            
            if (isset($route[0])) {
            
                if (isset($uris[0])) {
                    if (substr($route[0], 0, 1) == ":") {
                        $val[$route[0]] = $uris[0];
                    } else {
                        $val['mod'] = $uris[0];
                    }
                    
                    unset($uris[0]);
                }
                
                unset($route[0]);
            }
            
            if (isset($route[1])) {
            
                if (isset($uris[1])) {
                    if (substr($route[1], 0, 1) == ":") {
                        $val[$route[1]] = $uris[1];
                    } else {
                        $val['ctr'] = $uris[1];
                    }
                    
                    unset($uris[1]);
                }
                
                unset($route[1]);
                
            }                    
            
            foreach ($route as $key2 => $val2) {
                if (substr($val2, 0, 1) == ":" && isset($uris[$key2])) {
                    $val[$val2] = $uris[$key2];
                    unset($uris[$key2]);
                }
            }
            
            $pre = NULL;
            foreach ($uris as $val2) {
                if ($pre !== NULL) {

                    $val[$pre] = $val2;
                    $pre = NULL;
                
                } else {

                    $pre = $val2;
                }
            }
            unset($route, $pre);
            
            if (isset($val['mod']) && isset($val['mod']) && isset($val['mod'])) {
                foreach ($val as $key2 => $val2) {
                    $this->$key2 = $val2;
                }
                break;
            }
        }
    }
}
