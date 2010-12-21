<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->headtitle; ?></title>
    <link rel="stylesheet" href="/_cm/css/common.css" type="text/css" media="all" />
    <?php echo $this->headlink; ?>
</head>
<body>

<table id="topnav" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left">
	    <a href="#">Home</a>
    </td>
    <td align="right">
      <a href="#">Sign up</a>
      &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">Sign in</a>
    </td>
  </tr>
</table>

<div class="centerall" style="background: url(/_cm/img/navline-px.png) #e4f2fd bottom repeat-x;"> 
    <div class="wrapper"> 
        <div class="logo">Header</div>
        <ul id="homenav"> 
            <li><a href="#" class="current">Home</a></li> 
            <li><a href="#" >Explore</a></li> 
            <li><a href="#" >Register</a></li> 
        </ul> 
    </div> 
</div> 
 
<table class="wrapper">
    <tr>
        <?php if ($this->types !== NULL) { ?>
        <td width="220px">
        <?php print $this->types; ?>
        </td>
        <?php } ?>
        <td>
        <?php print $this->content; ?>
        </td>
    </tr>
</table>

<div id="footer" class="wrapper">
    <p>Footer</p>
</div>
</body>
</html>
