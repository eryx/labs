<?php
$this->headtitle = "Change photo";
$this->headlink = "<link rel=\"stylesheet\" href=\"/_user/css/manage.css\" type=\"text/css\" media=\"all\" />";
?>
<div class="managecommon">
<h2 class="title">Change photo</h2>

<div class="box">
<table border="0" cellpadding="0" cellspacing="10" > 
	<tr> 
		<td valign="bottom"><img src="<?php echo $this->entry_path?>/w100.png" /> <b>Normal size</b></td> 
		<td width="30px"></td> 
		<td valign="bottom"><img src="<?php echo $this->entry_path?>/w40.png" /> <b>Small size</b></td> 
	</tr> 
</table>
<p>You can upload a JPG, GIF, or PNG file. (Maximum size of 500KB) Do not upload photos containing children, pets, cartoons, celebrities, nudity, artwork or copyrighted images.</p>
<form id="changephoto" name="changephoto" enctype="multipart/form-data" action="/user/profile-manage/photodo" method="post">
  	<table border="0" cellpadding="0" cellspacing="0" >
  	    <tr>
  	        <td width="160px"><b>Select picture</b></td>
  	        <td><input id="attachment" name="attachment" size="40" type="file" /></td>
  	    </tr>
  	    <tr>
  	        <td></td>
  	        <td><input type="submit" value="Upload" />
  	    </tr>
  	</table>
</form>
</div>
</div>
