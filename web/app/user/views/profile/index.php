<?php
$this->headtitle = "Profile";
$this->headlink = "<link rel=\"stylesheet\" href=\"/_user/css/common.css\" type=\"text/css\" media=\"all\" />";
?>

<div id="profile">
    <h2 class="title"><?php echo $this->profile['name']?></h2>
    <table class="box" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="160px" valign="top"><img src="<?php echo $this->profile['photo_path']?>/w100.png" /></td>
            <td align="left" class="info"><?php echo $this->profile['content']?></td>
        </tr>
    </table>
</div>

