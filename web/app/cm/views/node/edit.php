<?php

$this->headlink = "<link rel=\"stylesheet\" href=\"/_cm/css/node.css\" type=\"text/css\" media=\"all\" />";

?>
    

<div class="navindex_title">
    <?php echo $entry['id'] ? 'Edit' : 'Add new'; ?>
</div>

<!-- TinyMCE/ -->
<script type="text/javascript" src="/_default/js/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="/_default/js/editor.js"></script>
<script type="text/javascript">

// init for media plugin
var current_media_plugin = 'content';

// init for rich editor
tinymceInitOptions['content_css'] = "/_default/js/editor.css";

tinyMCE.init(tinymceInitOptions);

//tinyMCE.execCommand("mceAddControl", false, 'content');
//tinyMCE.execCommand("mceAddControl", false, 'summary');


// insert medias

mediaplugin = {
    insert : function(text) {
        if (current_media_plugin == 'summary') {
            richEditor.go('summary', 'tinymce');
            tinyMCE.execCommand('mceInsertContent', false, text);
        } else {
            richEditor.go('content', 'tinymce');
            tinyMCE.execCommand('mceInsertContent', false, text);
        }
    }
}

</script>
<!-- /TinyMCE -->

<?php print $this->render('message-general'); ?>

<form id="nodeedit" name="nodeedit" action="/cm/node/save" method="post">
<input id="id" name="id" type="hidden" value="<?=$entry['id']?>" />

<table width="100%" class="edit_frame" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td>
        <!-- node title -->
        <div class="edit_item edit_item_title">Title<font color="red">*</font></div>
        <input class="edit_item_input" id="title" name="title" type="text" size="70" value="<?=$entry['title']?>" />

        <!-- node summary -->
        <div id="auto_summary_box" class="hideifnojs">
            <div class="edit_item_left edit_item_info">
                <div id="auto_summary_title" class="hideifnojs">
                    <span class="edit_item_title">Summary</span> 
                    <span id="summary_media_box" class="hideifnojs"><a href="javascript:;"  onclick="current_media_plugin = 'summary'; openWindow('/media/manage-editorplugin/?target=summary', 'upload', '800', '700')">[Insert Images]</a></span>
                </div>
            </div>
            <div class="edit_item_right edit_item_info">
                <input type="checkbox" id="auto_summary" name="auto_summary" onchange="changeAutoSummary()" value="1" <?php if (strlen($entry['summary']) == 0) {echo 'checked';} ?> /> Auto Summary
                <span id="summary_richeditor_ctrl" class="hideifnojs">
                    <a href="javascript:;" onclick="richEditor.go('summary', 'tinymce');">[Visual]</a>
                    <a href="javascript:;" onclick="richEditor.go('summary', 'html');">[HTML]</a>
                </span>
            </div>
            
            <div id="auto_summary_text" class="clear_both hideifnojs"><textarea style="width:100%" id="summary" name="summary" rows="10"><?=$entry['summary']?></textarea></div>                    
        </div>
        
        <div class="clear_both" />
        <!-- node content -->
        <div class="edit_item_left edit_item_info">
            <span class="edit_item_title">Content <font color="red">*</font></span> 
            <span id="content_media_box" class="hideifnojs"><a href="javascript:;"  onclick="current_media_plugin = 'content'; openWindow('/media/manage-editorplugin/?target=content', 'upload', '800', '700')">[Insert Images]</a></span>
        </div>
        <div class="edit_item_right edit_item_info">
            <div id="content_richeditor_ctrl" class="hideifnojs">
                <a href="javascript:;" onclick="richEditor.go('content', 'tinymce');">[Visual]</a>
                <a href="javascript:;" onclick="richEditor.go('content', 'html');">[HTML]</a>
            </div>
        </div> 
        <div class="clear_both" />
        <textarea style="width:100%" id="content" name="content" rows="30"><?=$entry['content']?></textarea>
    </td>
    
    <td width="30px"></td>
    
    <td width="280px" valign="top">
        <div class="edit_option_box">
            <div class="edit_option_title">Publish</div>
            <div class="edit_option_list">
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    
                    <tr>
                        <td align="right">Allow Comment</td>
                        <td>
                        <input type="checkbox" id="comment" name="comment" value="1" <?php if ($entry['comment'] == 1) {echo 'checked';} ?> />
                        </td>
                    </tr>
                    <tr>
                        <td align="right">Status</td>
                        <td>
                        <select id="status" name="status">
                        <option value="1" <?php if ($entry['status'] == 1) {echo 'selected';}?>> Publish </option>
                        <option value="2" <?php if ($entry['status'] == 2) {echo 'selected';}?>> Draft </option>
                        <option value="3" <?php if ($entry['status'] == 3) {echo 'selected';}?>> Private </option>
                        </select>
                        </td>
                    </tr>
                </table>
            </div>
                
            <div class="edit_button_commit"><input type="submit" name="Submit" class="input_button" value="Save" /></div>
        </div>
            
        <div class="edit_option_box">
            <div class="edit_option_title">Category</div>
            <div class="edit_option_list">
            <select id="cat" name="cat">
                    <?php foreach ($cats as $term): ?> 
                    <option value="<?=$term['id']?>" <?php if ($term['id'] == $entry['cat']) { echo 'selected'; } ?>> <?php echo $term['title']?> </option>
                    <?php endforeach;?>
            </select>
            
            </div>
        </div>
        
        <div class="edit_option_box">
            <div class="edit_option_title">Tags</div>
            <div class="edit_option_list">
            <input id="terms" name="terms" type="text" style="width:230px;" value="<?=$entry['terms']?>"/> 
            <br/>Separate multiple tags with commas: <b>Cats, Pet food, Dogs</b>
            </div>

        </div>
        
    </td>
</tr>
</table>
</form>

<script>

function changeAutoSummary() {
    
    if (document.getElementById('auto_summary').checked) {
        
        document.getElementById('auto_summary_title').className = 'hideifnojs';
        document.getElementById('auto_summary_text').className = 'clear_both hideifnojs';
        document.getElementById('summary_richeditor_ctrl').className = 'hideifnojs';

    } else {
    
        document.getElementById('auto_summary_title').className = '';
        document.getElementById('auto_summary_text').className = 'clear_both';
        document.getElementById('summary_richeditor_ctrl').className = '';
        
    }
}

function openWindow(url, title, width, height, text) {
    var nwin;        
    if (url=='' || width=='' || height=='') {
        return false;
    }
    sWidth  = screen.availWidth;
    sHeight = screen.availHeight;
    var l = (screen.availWidth - width) / 2;
    var t = (screen.availHeight - height) / 2;      
    nwin = window.open(url, title, 'left='+ l +', top='+ t +', width='+ width +', height='+ height +',scrollbars=yes,resizable=yes');
    nwin.focus();
}


/** content.summary */
document.getElementById('auto_summary_box').className = '';
changeAutoSummary();


/** content */
document.getElementById('content_media_box').className = '';
document.getElementById('content_richeditor_ctrl').className = '';
tinyMCE.execCommand("mceAddControl", false, 'content');

</script>
