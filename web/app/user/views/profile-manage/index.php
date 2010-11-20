<?php
$this->headtitle = "My Profile";
$this->headlink = "<link rel=\"stylesheet\" href=\"/_user/css/manage.css\" type=\"text/css\" media=\"all\" />";
?>

<div id="profile-manage">
<h2 class="title">My Profile</h2>
<!-- TinyMCE/ -->
<script type="text/javascript" src="/_default/js/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="/_default/js/editor.js"></script>
<script type="text/javascript">
  tinymceInitOptions['content_css'] = "/_default/js/editor.css";
  tinyMCE.init(tinymceInitOptions);
</script>
<!-- /TinyMCE -->

<form id="user_profile" name="user_profile" action="/user/profile-manage/do" method="post" >
  <input id="uid" name="uid" type="hidden" value="<?php echo $entry->uid?>" />
  <table class="box" width="100%" border="0" cellpadding="0" cellspacing="8" >
    <tr>
      <td width="120px" align="right" ><b>My name</b></td>
      <td width="20px"></td>
      <td><input id="name" name="name" type="text" size="16" value="<?php echo $entry->name?>" /></td>
    </tr>
    <tr>
      <td align="right" ><b>Gender</b></td>
      <td></td>
      <td>
      	Male <input id="gender" name="gender" type="radio" value="1" <?php if ($entry->gender == 1) { echo 'checked="checked"'; } ?> />
          Female <input name="gender" type="radio" value="0" <?php if ($entry->gender == 0) { echo 'checked="checked"'; } ?> />   
      </td>
    </tr>
    <tr>
      <td align="right" ><b>Birthday</b></td>
      <td></td>
      <td ><input id="birthday" name="birthday" type="text" size="16" value="<?php echo $entry->birthday?>" /> Example : 1970-01-01</td>
    </tr>
    <tr>
      <td align="right" ><b>Address</b></td>
      <td></td>
      <td ><input id="address" name="address" type="text" size="30" value="<?php echo $entry->address?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top" ><b>About me</b></td>
      <td></td>
      <td>
        <div id="switchEditorsBar" class="hideifnojs">	
	      <a href="javascript:;" onclick="richEditor.go('content', 'tinymce');">[Visual]</a>
          <a href="javascript:;" onclick="richEditor.go('content', 'html');">[HTML]</a>
        </div>
        <textarea id="content" name="content" style="width:100%" rows="20" ><?php echo $entry->content?></textarea>
      </td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><input class="input_button" type="submit" name="submit" value="Save" /></td>
    </tr>
  </table>
</form>
<script>
  document.getElementById('switchEditorsBar').className = '';
  tinyMCE.execCommand("mceAddControl", false, 'content');
</script>
</div>
