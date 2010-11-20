<?php
$this->headtitle = "Change password";
$this->headlink = "<link rel=\"stylesheet\" href=\"/_user/css/manage.css\" type=\"text/css\" media=\"all\" />";
?>
<div class="managecommon">
<h2 class="title">Change password</h2>
<form name="user_accountpwd" action="/user/account-manage/pwddo" method="post" >
  <table class="box" width="100%" border="0" cellpadding="0" cellspacing="10" >
    <tr>
      <td width="200px" align="right" >Current password</td>
      <td ><input id="pass_current" name="pass_current" type="password" /></td>
    </tr>
    <tr>
      <td align="right" >New password</td>
      <td ><input id="pass" name="pass" type="password" /></td>
    </tr>
    <tr>
      <td align="right" >Confirm new password</td>
      <td ><input id="pass_confirm" name="pass_confirm" type="password" /></td>
    </tr>
    <tr>
      <td></td>
      <td ><input type="submit" name="submit" value="Submit" /></td>
    </tr>
  </table>
</form>
</div>
<script>document.user_accountpwd.pass_current.focus();</script>


