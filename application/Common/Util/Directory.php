<?php
defined('SYS_ENTRY') or die('Access Denied!');


/**
 * Common_Util_Directory
 */
final class Common_Util_Directory
{
    /**
     * 
     *
     * @param string $uuid
     * @return bool
     */
    public static function mkdir($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $dirpath = '';
        foreach ($dirs as $directory) {

            if ($directory == null || $directory == "") {
                continue;
            } else if ($directory == ".." || $directory == ".") {
                $dirpath .= $directory;
            } else {
                $dirpath .= '/'.$directory;
            }

            if (!is_dir($dirpath)) {
                mkdir($dirpath, $mode);
            }
        }
    }

    /**
     *  
     *
     * @param string $uuid
     * @return bool
     */
    public static function mkfiledir($path, $mode = 0777)
    {
        $path = pathinfo($path);
        $path = $path['dirname'];
        self::mkdir($path, $mode);
    }
}
