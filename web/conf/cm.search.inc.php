<?php
defined('SYS_ROOT') or die('Access Denied!');

$config = array();

$config['list'] = array(
'_layout' => 'layout/list',
'views' => array(
    array('name' => 'content',
        'view' => 'list/simple',
        'data' => array('api' => 'default',
            'datax' => 'data_entry',
            'output' => 'feeds'),
        'query' => array('q' => ':q',
            'attr' => array(
                'uid' => ':uid',
            ),
            'attrs' => array(
                'type' => ':type',
            ),
            'sortby' => array(
                'created' => ':created',
                'updated' => ':updated',
            ),
        ),
    ),
    array('name' => 'types',
        'view' => 'list/types',
        'data' => array('api' => 'default',
            'datax' => 'data_type',
            'output' => 'feeds'),
        'query' => array(
            'attrs' => array('type' => 'type')
        ),
    )
)
);

$config['view'] = array(
    '_layout' => 'layout/view',
    array('name' => 'content',
        'view' => 'view/simple',
        'data' => array('api' => 'default',
            'datax' => 'data_entry'),
        'query' => array(
            'attr' => array(
                'id' => ':id',
            )
        ),
    ),
    array('name' => 'comments',
        'view' => 'list/simple',
        'data' => array('api' => 'default',
            'datax' => 'data_entry'),
        'query' => array(
            'attr' => array(
                'pid' => ':pid',
                'type' => 'comment',
            )
        ),
    ),
    array('name' => 'commit',
        'view' => 'edit/simple',
        'data' => array('api' => 'none')
    ),
);

return $config;
