<?php
defined('SYS_ROOT') or die('Access Denied!');


/**
 * Set the server timezone
 * see: http://us3.php.net/manual/en/timezones.php
 */
date_default_timezone_set("Asia/Shanghai");

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

$config = array();

$config['baseurl'] = '';

$config['routes'] = array(
    array('_route' => 'home/:uname/:app/:method',
        'mod' => 'cm', 'ctr' => 'user', 'act' => 'index', 'app' => 'blog', 'method' => 'index'),
    array('_route' => 'doc/:method',
        'mod' => 'cm', 'ctr' => 'app', 'act' => 'index', 'inst' => 'doc'),
    array('_route' => 'portal/:method',
        'mod' => 'cm', 'ctr' => 'app', 'act' => 'index',
        'inst' => 'portal', 'method' => 'index'),
    array('_route' => ':mod/:ctr/:act', 
        'mod' => 'cm', 'ctr' => 'index', 'act' => 'index'),
);

$config['database'] = array('adapter' => 'pdo_mysql',
    //'params' => array('dbname' => SYS_ROOT .'data/database.sqlite')
    'params' => array('host' => '127.0.0.1', 'dbname' => 'hooto_v5', 'username' => 'root', 'password' => '123456', 'charset' => 'utf8')
);

$config['database2'] = array('adapter' => 'pdo_mysql',
    //'params' => array('dbname' => SYS_ROOT .'data/database.sqlite')
    'params' => array('host' => '127.0.0.1', 'dbname' => 'hooto_v5', 'username' => 'root', 'password' => '123456')
);
