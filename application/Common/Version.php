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
 * @package    Common_Version
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Version.php 796 2010-01-22 16:58:48Z onerui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ENTRY') or die('Access Denied!');

/**
 * Class Common_Version
 *
 * @category   Common
 * @package    Common_Version
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Common_Version
{
    /**
     * Version identification - see compareVersion()
     */
    const VERSION  = '0.2.0.alpha.6';
    
    /**
     * Revision identification
     */
    const REVISION = '$LastChangedRevision: 719 $';
    
    /**
     * Date identification
     */
    const DATE = '$LastChangedDate: 2009-08-16 01:38:07 +0800 (Sun, 16 Aug 2009) $';
    

    /**
     * Compare the specified version string $version
     * with the current Common_Version::VERSION of the SmartKit.
     *
     * @param  string  $version  A version string (e.g. "1.0.0").
     * @return boolean           -1 if the $version is older,
     *                           0 if they are the same,
     *                           and +1 if $version is newer.
     *
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        return version_compare($version, strtolower(self::VERSION));
    }

    /**
     * Get the version
     *
     * @return string 
     *
     */
    public function getVersion()
    {
        return self::VERSION;
    }
    
    /**
     * Get the last changed revision
     *
     * @return string 
     *      using svn:keywords LastChangedRevision
     *
     */
    public function getRevision()
    {
        return preg_replace('%\$'.'LastChangedRevision: (.*?) \$%i', '$1', self::REVISION);
    }
    
    /**
     * Get the last changed date
     *
     * @return string
     *      using svn:keywords LastChangedDate
     *
     */
    public function getDate()
    {
        return preg_replace('%\$'.'LastChangedDate: (.*?) \((.*?)\) \$%i', '$1', self::DATE);
    }
}
