<?php
defined('SYS_ROOT') or die('Access Denied!');

$config = array();

$config['entry'] = array(
    'id' => array('type' => 'urn:uuid', 'size' => 36, 'label' => 'ID'),
    'title' => array('type' => 'varchar', 'size' => 200, 'lable' => 'Title'),
    'summary' => array('type' => 'text', 'size' => 5000, 'lable' => 'Summary'),
    'content' => array('type' => 'text', 'size' => 100000, 'lable' => 'Content'),
    'updated' => array('type' => 'datetime', 'lable' => 'Updated'),
    'published' => array('type' => 'datetime', 'lable' => 'Published'),
    'status' => array('type' => 'int', 'default' => 1),
);

$config['types'] = array('doc', 'link');

$config['pagelets']['index'] = array(
    'layout' => 'list',
    'views' => array(
        array('name' => 'content',
            'view' => 'entry/list',
            'output' => 'feeds',
            'query' => array('p' => NULL, 'type' => NULL, 'start' => NULL),
            'sortby' => array('published DESC'),
        ),
        array('name' => 'types',
            'entry' => 'data_type',
            'view' => 'list/types',
            'output' => 'feeds',
        )
    )
);

$config['pagelets']['view'] = array(
    'layout' => 'list',
    'views' => array(
        array('name' => 'content',
            'view' => 'entry/view',
            'output' => 'feeds',
            'query' => array('id' => NULL),
        ),
    )
);

return $config;
