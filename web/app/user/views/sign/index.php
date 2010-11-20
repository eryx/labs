<div>
    <h2>Sign in to System</h2>
    <form name="signinform" action="/user/sign/indo" method="post">
    <?php echo $this->render('message-general');?>
    <div>Please enter your user name and password</div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="160" align="left"><b>Username</b></td>
            <td>
                <input id="uname" name="uname" type="text" value="<?=$this->uname?>" />
                <script>document.signinform.uname.focus();</script>
            </td>
        </tr>
        <tr>
            <td align="left"><b>Password</b></td>
            <td><input id="pass" name="pass" type="password" /></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td><input type="checkbox" id="persistent" name="persistent" value="1" /> Stay signed in</td>
        </tr>
        <tr>
            <td></td>
            <td><input class="input_button" type="submit" name="Submit" value="Sign in" /></td>
        </tr>
        <tr>
            <td align="right"></td>
            <td>
                <a href="/user/sign/pass">Forget your ID or password</a>
            </td>
        </tr>
    </table>
    </form>
</div>

