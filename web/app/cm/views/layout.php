<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <link rel="stylesheet" href="/_cm/css/common.css" type="text/css" media="all" />
</head>
<body>
<?php 
//echo $this->render('general-topnav.phtml');
//echo $this->render('message-general.phtml');
?>
<div id="header" style="background: url(/_cm/img/navline-px.png) #e4f2fd bottom repeat-x;">
    <div class="wrapper">
        <h1>Header</h1>
        <ul>
            <li><a href="#" class="current">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
        </ul>
    </div>
</div>

<div class="wrapper">
<?php print $this->content; ?>
</div>

<div id="footer" class="wrapper">
    <p>Footer</p>
</div>
</body>
</html>
