<?php

$table = isset($params['data_base']) ? $params['data_base'] : 'data_type';

$db = Core_Dao::factory(array('name' => $table));

$where = array();
$where['in.id'] = $params['query_set']['types'];

$order = array('weight DESC');
$feed = $db->getList($where, $order, 99);

foreach ($feed as $entry) {
    echo "<div>{$entry['title']}</div>";
}
