<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->headtitle; ?></title>
    <link rel="stylesheet" href="/_cm/css/common.css" type="text/css" media="all" />
    <?php echo $this->headlink; ?>
</head>
<body>

<style>
body {padding:100px; background-color:#e4f2fd;}
#msg_container {text-align:left; background-color:#ffffff; border:#c6d9e9 3px solid;}
#msg_container td {padding:20px;}
#msg_container .msg_body {padding-top:10px; font-size:14pt; font-weight:bold; border-bottom:#c6d9e9 1px solid;}
#msg_container ul {padding:20px 0px 20px 5px;}
#msg_container ul li {list-style-type:none;}
#msg_container a {text-decoration:underline;}
</style>

<div class="centerbox">
<table id="msg_container" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      	<td width="80px" align="center" valign="top">
      	    <img src="/_cm/img/<?=$this->message['type']?>-large.png" border="0" />
      	</td>
      	<td>
      	    <div class="msg_body"><?=$this->message['body']?></div>
      	    <ul>
      	        <?php foreach ($this->message['links'] as $link): ?> 
      	        <li>&#8250; <a href="<?=$link['url']?>"><?=$link['title']?></a></li>
      	        <?php endforeach; ?>
      	    </ul>
      	</td>
    </tr>
</table>
</div>

</body>
</html>
