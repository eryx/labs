<div>
    <h2 class="title">Sign up</h2>
    <?php echo $this->render('message-general');?>
    <form id="signup" name="signup" action="/user/sign/updo" method="post">
    <table width="100%" border="0" cellpadding="0" cellspacing="10">
        <tr>
            <td width="160px" align="right" ><b>Username</b></td>
            <td>
                <input id="uname" name="uname" type="text" size="16" value="<?=$this->uname?>" />
            </td>
        </tr>
        <tr>
            <td align="right"><b>Email</b></td>
            <td><input id="email" name="email" type="text" size="20" maxlength="50" value="<?=$this->email?>" /></td>
        </tr>
        <tr>
            <td align="right"><b>Password</b></td>
            <td>
                <input id="pass" name="pass" type="password" size="16" value="<?=$this->pass?>" />
            </td>
        </tr>
        <tr>
            <td align="right"><b>Re-type Password</b></td>
            <td><input id="repass" name="repass" type="password" size="16" value="<?=$this->repass?>" /></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td><input type="submit" value="Sign up" /></td>
        </tr>
    </table>
    </form>
</div>

