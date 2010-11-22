<?php
foreach ($feeds as $entry) {
    echo "<h3><a href=\"/{$this->inst}/view/?id={$entry['id']}\">{$entry['title']}</a></h3>";
    echo "<div>{$entry['summary']}</div>";
    echo "<div>{$entry['content']}</div>";
}
$pagerurl = "/{$this->inst}/{$this->act}/?";
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
