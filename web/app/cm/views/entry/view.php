<h3><?php echo $entry['title']?></h3>
<div>published by <?php echo $entry['uname']?> on <?php echo $entry['published']?></div>
<p><?php echo $entry['summary']?></p>
<p><?php echo $entry['content']?></p>
<?php 
if ($entry['cat'] > 0) { 
    $db = Core_Dao::factory(array('name' => 'taxonomy_term_user'));    
    $cat_entry = $db->getById($entry['cat']);
    echo "<div>Category: <a href=\"/{$this->inst}/term/{$cat_entry['id']}\">{$cat_entry['title']}</a></div>";
}
print_r($_GET);
?>
