<?php
/**
 * SmartKit
 *
 * @copyright  Copyright (c) 2004-2009 HOOTO.COM
 * @license    http://www.gnu.org/copyleft/gpl.html
 * @version    $Id: index.php 862 2010-05-10 15:49:48Z evorui $
 */

xhprof_enable();

define('SYS_ENTRY', '..');

$time_start = microtime(true);

// Define path to application directory
define('APPLICATION_PATH', realpath(SYS_ENTRY.'/application'));
define('ROOT_PATH', realpath(SYS_ENTRY));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure application|library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(APPLICATION_PATH,
    realpath(SYS_ENTRY.'/library'),
    get_include_path(),
)));


/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();

// Debug ...
if (isset($_REQUEST['debug'])) {


    $time_end = microtime(true);
    $time = 1000 * ($time_end - $time_start);
    echo "<pre>";
    echo "<-- total: $time ms -->\n";

    //
    $gdb = Zend_Registry::get('db');
    $ret = $gdb->getProfiler()->getQueryProfiles();
    $query = $gdb->getProfiler()->getTotalNumQueries();
    $querytime = 1000 * $gdb->getProfiler()->getTotalElapsedSecs();

    echo "<-- querys: $query in $querytime ms -->\n";
    foreach ($ret as $v) {
        echo $v->getQuery()."\n";
    }
    echo "</pre>";
    
    $xhprof_data = xhprof_disable();
    
    include_once "xhprof_lib/utils/xhprof_lib.php";
    include_once "xhprof_lib/utils/xhprof_runs.php";

    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof");

    echo "<script language=\"javascript\"> 
            window.open('http://cm.dev.com/xhprof_html/index.php?run=$run_id&source=xhprof'); 
            </script>   ";

}
