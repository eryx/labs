<?php

$acturl = "/{$this->inst}/{$this->act}/?";

$p  = isset($reqs->p) ? intval($reqs->p) : 1;
$pagerurl = $acturl;


$db     = Core_Dao::factory(array('name' => 'data_entry'));
$dbterm = Core_Dao::factory(array('name' => 'taxonomy_term_user'));

$where = array();
$limit = 10;

$type = isset($reqs->type) ? $reqs->type : NULL;
if ($type === NULL) {
    $where['in.type'] = $params['query_set']['types'];
} else {
    $where['type'] = $type;
    $pagerurl .= "&type={$type}";
}

if (isset($reqs->params['cat'])) {
    $where['cat'] = intval($reqs->params['cat']);
    $pagerurl .= "&cat={$reqs->params['cat']}";
}

if (isset($reqs->params['term'])) {
    $where['like.terms'] = "%{$reqs->params['term']}%";
    $pagerurl .= "&term={$reqs->params['term']}";
}

$order = array('published DESC');

if (isset($reqs->q)) {
    $where['like.title'] = "%{$reqs->q}%";
    $pagerurl .= "&q={$reqs->q}";
}
$feed = $db->getList($where, $order, $limit, ($p - 1) * $limit);

$count = $db->getCount($where);
$pager = Core_Util_Pager::get($p, $count, $limit);


foreach ($feed as $key => $entry) {
    
    $feed[$key]['link'] = "/{$this->inst}/view/?id={$entry['id']}"; // TODO
    $feed[$key]['avatar'] = "/user/profile/avatar/{$entry['uname']}-w40.png";
    $feed[$key]['link_profile'] = "/user/profile/{$entry['uname']}";
    $feed[$key]['terms'] = explode(",", $entry['terms']);
    
    if ($entry['cat'] > 0) {
        $feed[$key]['_cat_entry'] = $dbterm->getById($entry['cat']);
    }
    
    if (strlen($entry['summary']) > 1) {
        $feed[$key]['summary'] = Core_Util_Format::ubb2html(Core_Util_Format::textHtmlFilter($entry['summary']));
    } else {
        $feed[$key]['summary'] = Core_Util_Format::autoParagraph(Core_Util_Format::cutstr(Core_Util_Format::ubbClear($entry['content']), 400));
    }
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
      <?php if (isset($entry['_cat_entry']['id'])) { ?>
      &nbsp; <img src="/_cm/img/folder.png" align="absmiddle" title="Category" />&nbsp; <a href="<?php echo $acturl?>cat=<?php echo $entry['_cat_entry']['id']?>"><?=$entry['_cat_entry']['title']?></a>
      <?php } ?>                      
      <?php if (count($entry['terms']) > 0) { ?>
      &nbsp; <img src="/_cm/img/tag_blue.png" align="absmiddle" /> 
      <?php
        foreach ((array)$entry['terms'] as $term) { ?> 
        &nbsp;<a href="<?php echo $acturl?>term=<?php echo $term?>"><?=$term?></a>
      <?php } } ?>
    </div>
   
    <div class="entrybody"><?=$entry['summary']?></div>
                    
    <div class="bottominfo">
      <a href="/cm/node/del?id=<?php echo $entry['id']?>">&#187; Delete</a>
      <a class="cgray" href="/cm/node/edit?id=<?php echo $entry['id']?>">&#187; Edit</a>
      <a href="<?=$entry['link']?>" target="_blank">&#187; Detail</a>
    </div>
  </dd>
  <?php endforeach; ?>
</dl>
<?php 
if ($count == 0) {
    return;
}
?>
<ul class="pager">
    <li><?php echo 'Items'.' '.$pager['itemFrom'].' - '.$pager['itemTo'].' of '.$pager['itemCount']; ?></li>    
    <?php if (isset($pager['first'])) { ?>
    <li><a href="<?=$pagerurl?>&p=<?=$pager['first']?>">First</a></li>
    <?php } if (isset($pager['previous'])) { ?>
    <li><a href="<?=$pagerurl?>&p=<?=$pager['previous']?>">Previous</a></li>
    <?php } foreach ($pager['list'] as $page) { ?>
    <li><a href="<?=$pagerurl?>&p=<?=$page['page']?>" <?php if ($page['isCurrent']) {echo 'class="current"';}?>><?=$page['page']?></a></li>
    <?php } if (isset($pager['next'])) { ?>
    <li><a href="<?=$pagerurl?>&p=<?=$pager['next']?>">Next</a></li>
    <?php } if (isset($pager['last'])) { ?>
    <li><a href="<?=$pagerurl?>&p=<?=$pager['last']?>">Last</a></li>
    <?php } ?>
</ul>
