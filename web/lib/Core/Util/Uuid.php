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
 * @package    Core_Util
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: Uuid.php 799 2010-01-24 15:33:43Z evorui $
 */
 
/** ensure this file is being included by a parent file */
defined('SYS_ROOT') or die('Access Denied!');

/**
 * Class Core_Util_Uuid
 *
 * @category   Core
 * @package    Core_Util
 * @copyright  Copyright 2010 HOOTO.COM
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
final class Core_Util_Uuid
{
    /**
     * Generates a Universally Unique IDentifier, version 4.
     *
     * RFC 4122 (http://www.ietf.org/rfc/rfc4122.txt) defines a special type of Globally
     * Unique IDentifiers (GUID), as well as several methods for producing them. One
     * such method, described in section 4.4, is based on truly random or pseudo-random
     * number generators, and is therefore implementable in a language like PHP.
     *
     * We choose to produce pseudo-random numbers with the Mersenne Twister, and to always
     * limit single generated numbers to 16 bits (ie. the decimal value 65535). That is
     * because, even on 32-bit systems, PHP's RAND_MAX will often be the maximum     *signed*
     * value, with only the equivalent of 31 significant bits. Producing two 16-bit random
     * numbers to make up a 32-bit one is less efficient, but guarantees that all 32 bits
     * are random.
     *
     * The algorithm for version 4 UUIDs (ie. those based on random number generators)
     * states that all 128 bits separated into the various fields (32 bits, 16 bits, 16 bits,
     * 8 bits and 8 bits, 48 bits) should be random, except : (a) the version number should
     * be the last 4 bits in the 3rd field, and (b) bits 6 and 7 of the 4th field should
     * be 01. We try to conform to that definition as efficiently as possible, generating
     * smaller values where possible, and minimizing the number of base conversions.
     *
     * @copyright   Copyright (c) CFD Labs, 2006. This function may be used freely for
     *              any purpose ; it is distributed without any form of warranty whatsoever.
     * @author      David Holmes <dholmes@cfdsoftware.net>
     *
     * @return  string  A UUID, made up of 32 hex digits and 4 hyphens.
     */
    public static function create()
    {
        // The field names refer to RFC 4122 section 4.1.2
        return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
            mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
            mt_rand(0, 65535), // 16 bits for "time_mid"
            mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
            bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
                // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
                // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
                // 8 bits for "clk_seq_low"
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node" 
        );
    }
    
    /**
     * Check the validity of a given uuid
     *
     * ([0-9a-f]{8})-([0-9a-f]{4})-([0-9a-f]{4})-([0-9a-f]{4})-([0-9a-f]{12})
     * ex. 00000000-0000-0000-0000-000000000000
     *
     * @param string $uuid
     * @return bool
     */
    public static function isValid($uuid)
    {
        $uuid = strtolower($uuid);
        if (preg_match('/^([0-9a-f]{8})-([0-9a-f]{4})-([0-9a-f]{4})-([0-9a-f]{4})-([0-9a-f]{12})$/', $uuid)) {
            return true;
        } else {
            return false;
        }
    }
}
