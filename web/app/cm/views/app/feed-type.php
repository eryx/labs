<?php

$table = isset($params['data_base']) ? $params['data_base'] : 'data_type';

$db = Core_Dao::factory(array('name' => $table));

$where = array();
$where['in.id'] = $params['query_set']['types'];

$order = array('weight DESC');
$feed = $db->getList($where, $order, 99);

?>
<div class="sideblock">
  <h3>Content</h3>
  <ul>
    <?php foreach ($feed as $entry) { 
    $class = (isset($reqs->type) && $reqs->type == $entry['id']) ? "current" : "";
    ?>
    <li><a class="<?php echo $class?>" href="/<?php echo $reqs->inst?>/<?php echo $reqs->method?>/?type=<?=$entry['id']?>"><?php echo $entry['title']?></a></li>
    <?php } ?>
  </ul>
</div>
