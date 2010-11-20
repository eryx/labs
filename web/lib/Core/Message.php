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
 * @package    Core_Message
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Message.php 799 2010-01-24 15:33:43Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class Core_Message
 *
 * @category   Core
 * @package    Core_Message
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Core_Message
{
    private static $_types = array('success', 'warning', 'notice');
    
    private static $_instance = array(
        'type' => 'error', 
        'message' => 'Intestinal error',
        'links' => array());

    public static function get($type, $body, $links = array())
    {
        if (in_array($type, self::$_types)) {
            self::$_instance['type'] = $type;
        }
        
        if (!is_null($body)) {
            self::$_instance['body'] = $body; 
        }
        
        foreach ((array)$links as $key => $link) {
            if (isset($link['url']) && !is_null($link['url'])
                && isset($link['title']) && !is_null($link['title'])) {
                self::$_instance['links'][$key] = array(
                    'url' => $link['url'],
                    'title' => $link['title']
                );
            }
        }
        
        return self::$_instance;
    }
}
