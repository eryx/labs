<?php
$this->headtitle = "Change password";
$this->headlink = "<link rel=\"stylesheet\" href=\"/_user/css/manage.css\" type=\"text/css\" media=\"all\" />";
?>
<div class="managecommon">
<h2 class="title">Edit email</h2>
<form id="account_email" name="account_email" action="/user/account-manage/emaildo" method="post" >
  <table class="box" width="100%" border="0" cellpadding="0" cellspacing="10" >
    <tr>
      <td width="200px" align="right" >Email</td>
      <td ><input id="email" name="email" type="text" value="<?php echo $entry['email']?>" /></td>
    </tr>
     <tr>
      <td width="200px" align="right" >Password</td>
      <td ><input id="pass" name="pass" type="password" /></td>
    </tr>
    <tr>
      <td></td>
      <td ><input type="submit" name="submit" value="Submit" /></td>
    </tr>
  </table>
</form>
</div>
