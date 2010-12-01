<?php

$table = isset($params['data_base']) ? $params['data_base'] : 'data_entry';

$db = Core_Dao::factory(array('name' => $table));

$where = array();
$limit = 10;

$type = isset($reqs->type) ? $reqs->type : NULL;
if ($type === NULL) {
    $where['in.type'] = $params['query_set']['types'];
}

$order = isset($reqs->sortby) ? $reqs->sortby : array();
$p  = isset($reqs->p) ? intval($reqs->p) : 1;
if ($p < 1) {
    $p = 1;
}
$feed = $db->getList($where, $order, $limit, ($p - 1) * $limit);

$count = $db->getCount($where);
$pager = Core_Util_Pager::get($p, $count, $limit);
$pagerurl = "/{$this->inst}/{$this->act}/?";

foreach ($feed as $entry) {
    echo "<h3><a href=\"/{$this->inst}/view/?id={$entry['id']}\">{$entry['title']}</a></h3>";
    echo "<div>{$entry['summary']}</div>";
    echo "<div>{$entry['content']}</div>";
}

?>
<ul class="pager">
    <li><?php echo 'Items'.' '.$pager['itemFrom'].' - '.$pager['itemTo'].' of '.$pager['itemCount']; ?></li>    
    <?php if (isset($pager['first'])) { ?>
    <li><a href="<?=$pagerurl?>p=<?=$pager['first']?>">First</a></li>
    <?php } if (isset($pager['previous'])) { ?>
    <li><a href="<?=$pagerurl?>p=<?=$pager['previous']?>">Previous</a></li>
    <?php } foreach ($pager['list'] as $page) { ?>
    <li><a href="<?=$pagerurl?>p=<?=$page['page']?>" <?php if ($page['isCurrent']) {echo 'class="current"';}?>><?=$page['page']?></a></li>
    <?php } if (isset($pager['next'])) { ?>
    <li><a href="<?=$pagerurl?>p=<?=$pager['next']?>">Next</a></li>
    <?php } if (isset($pager['last'])) { ?>
    <li><a href="<?=$pagerurl?>p=<?=$pager['last']?>">Last</a></li>
    <?php } ?>
</ul>
