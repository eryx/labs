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

foreach ($feed as $key => $entry) {
    $feed[$key]['link'] = "/{$this->inst}/view/?id={$entry['id']}";
    $feed[$key]['avatar'] = "/user/profile/avatar/{$entry['uname']}-w40.png";
    $feed[$key]['link_profile'] = "/user/profile/{$entry['uname']}";
    $feed[$key]['terms'] = explode(",", $entry['terms']);
}

?>

<dl class="entrylist">
  <?php foreach ($feed as $entry): ?>
  <dt>
    <a href="<?php echo $entry['link']?>" target="_blank"><?php echo $entry['title']?></a> 
    <span class="cgray">[<?=$entry['type']?>]</span>
  </dt>
  <dd>
    <div class="entryinfo">
      <img src="<?=$entry['avatar']?>" title="<?=$entry['uname']?>" width="18px" height="18px"/> <a href="<?php echo $entry['link_profile']?>"><b><?=$entry['uname']?></b></a> on <?php echo date('Y-m-d', strtotime($entry['created']));?>
                            
      <?php if (count($entry['terms']) > 0) { ?>
      <img src="/_cm/img/tag_blue.png" align="absmiddle" /> 
      <?php }
        foreach ((array)$entry['terms'] as $term) { ?> 
        &nbsp;<a href="#<?=$term?>"><?=$term?></a>
      <?php } ?>
    </div>
   
    <div class="entrybody"><?=$entry['summary']?></div>
                    
    <div class="bottominfo">
      <a href="<?=$entry['link']?>" target="_blank">&#187; Detail</a>
    </div>
  </dd>
  <?php endforeach; ?>
</dl>                   
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
