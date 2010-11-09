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
 * @category   Core_Controller
 * @package    Core_Controller
 * @copyright  Copyright 2004-2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Core_Controller.php 856 2010-05-07 16:05:39Z evorui $
 */

/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');


class Core_Controller
{
    public $views   = array();

	public $layout	= 'layout';
	
    public function render()
	{
	    $layoutPath = SYS_ROOT."application/cm/views/{$this->layout}.php";
    	$output = load::view($layoutPath, $this->views);

		print $output;
	}
}
