<?php
$table = isset($params['data_base']) ? $params['data_base'] : 'data_entry';

$db = Core_Dao::factory(array('name' => $table));

$id = isset($reqs->id) ? $reqs->id : '';


$entry = $db->getById($id);
//echo "<pre>";
//print_r($entry);
//echo "</pre>";
if ($entry['cat'] > 0) {
    $db = Core_Dao::factory(array('name' => 'taxonomy_term_user'));    
    $cat_entry = $db->getById($entry['cat']);
} else {
    $cat_entry = array();
}
if (strlen($entry['terms'])) {
    $entry['terms'] = explode(",", $entry['terms']);
} else {
    $entry['terms'] = array();
}
$entry['published'] = date("Y:m:d h:i", strtotime($entry['published']));
$entry['updated']   = date("Y:m:d h:i", strtotime($entry['updated']));
$entry['content']   = Core_Util_Format::ubb2html(Core_Util_Format::textHtmlFilter($entry['content']));
$this->headtitle = $entry['title'];
?>

<div class="entry-view">
    <div class="entry-header">
        <h1 class="entry-title"><?=$entry['title']?></h1>
   	    <div class="entry-info">
   	        <img src="/_cm/img/date.png" align="absmiddle" title="Created" /> <?=$entry['published']?>&nbsp;&nbsp;
            <?php if (count($cat_entry) > 1) { ?>
            <img src="/_cm/img/folder.png" align="absmiddle" title="Category" /> <a href="/<?php echo $this->inst?>/term/<?php echo $cat_entry['id']?>"><?=$cat_entry['title']?></a>
            <?php } ?>
        </div>
    </div>
   	<div class="entry-content"><?=$entry['content']?></div>
   	<?php
   	if (count($entry['terms']) > 0) {
   	?>
    <div class="clear_both">
        <img src="/_cm/img/tag_blue.png" align="absmiddle" title="Tags" /> Tags:
        <?php foreach ($entry['terms'] as $term): ?> 
            &nbsp;&nbsp;<a href="#<?=$term?>"><?=$term?></a>
       	<?php endforeach; ?>
    </div>
    <?php } ?>
</div>
    
