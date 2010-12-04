<?php
defined('SYS_ROOT') or die('Access Denied!');

$config = array();

// types of all
$config['types'] = array('doc', 'link');

// common
$config['entry'] = array(
    'id' => array('type' => 'urn:uuid', 'size' => 36, 'label' => 'ID'),
    'title' => array('type' => 'varchar', 'size' => 200, 'lable' => 'Title'),
    'summary' => array('type' => 'text', 'size' => 5000, 'lable' => 'Summary'),
    'content' => array('type' => 'text', 'size' => 100000, 'lable' => 'Content'),
    'updated' => array('type' => 'datetime', 'lable' => 'Updated'),
    'published' => array('type' => 'datetime', 'lable' => 'Published'),
    'status' => array('type' => 'int', 'default' => 1, 'lable' => 'Status'),
    'cat' => array('type' => 'term_int', 'term_vid' => 1, 'term_entry' => 'taxonomy_entry_user', 'size' => 1, 'user' => 1, 'lable' => 'Category'),
);

// entry::doc
$config['type']['doc'] = $config['entry'];
$config['type']['doc']['page'] = array(
    'type' => 'int', 'default' => 0, 'lable' => 'Pages',
);

// entry::link
$config['type']['link'] = $config['entry'];
$config['type']['link']['content']['lable'] = 'Url';


// default page config
$config['pagelets']['index'] = array(
    'layout' => 'app/layout',
    'views' => array(
        array('pagelet' => 'content',
            'script' => 'app/feed',
            'params' => array(
                'data_base' => 'data_entry',
                'query_set' => array(
                    'types' => array('doc', 'video', 'blog')
                )
            )
        ),
        array('pagelet' => 'search',
            'script' => 'app/side-search',
        ),
        array('pagelet' => 'types',
            'script' => 'app/feed-type',
            'params' => array(
                'data_base' => 'data_type',
                'query_set' => array(
                    'types' => array('doc', 'video', 'blog')
                )    
            )
        ),
    )
);

$config['pagelets']['view'] = array(
    'layout' => 'app/layout',
    'views' => array(
        array('pagelet' => 'content',
            'script' => 'app/entry',
            'params' => array(
                'data_base' => 'data_entry',
                'query_set' => array(
                    'types' => array('doc', 'video', 'blog')
                )
            )
        ),
    )
);

return $config;
