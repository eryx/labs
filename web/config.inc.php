<?php
defined('SYS_ROOT') or die('Access Denied!');


/*
 * Set the server timezone
 * see: http://us3.php.net/manual/en/timezones.php
 */
date_default_timezone_set("Asia/Shanghai");

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

$config = array();

$config['baseurl'] = '';

$config['routes'] = array(
    array('_route' => ':mod/:ctr/:act', '_type' => 'std', 
        'mod' => 'cm', 'ctr' => 'index', 'act' => 'index'),
);
