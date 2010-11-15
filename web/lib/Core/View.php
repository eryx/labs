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
 * @category   Core_View
 * @package    Core_View
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */

/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');


class Core_View
{
    private $_paths  = NULL;

    /** public function __construct()
    {
    } */
    
    public function __set($key, $val)
    {
        if ('_' != substr($key, 0, 1)) {
            $this->$key = $val;
        }
    }
    
    public function setPath($path)
    {
        $this->_paths[] = rtrim($path, '/');
        krsort($this->_paths);
    }
    
    public function load($file, $vars = NULL)
	{
		if (is_array($vars)) {
			foreach ($vars as $key => $val) {
				$$key = $val;
			}
		}
        unset($vars);

		ob_start();

		include $file;
		
		return ob_get_clean();
	}
	
	public function render($name = NULL, $vars = NULL)
	{
	    $buffer = "";
	  
	    foreach ($this->_paths as $val) {
            $file = $val."/{$name}.php";            
            if (file_exists($file)) {
                $buffer = $this->load($file, $vars);
	            unset($file);
            }
        }	        
	    
	    return $buffer;
	}
	
	/** public function clear()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $val) {
            if ('_' != substr($key, 0, 1)) {
                unset($this->$key);
            }
        }
    } */
}
