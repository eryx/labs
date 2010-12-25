<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>An error occurred</title>
  <link rel="stylesheet" href="/_cm/css/error.css" type="text/css" media="all" />
</head>
<body>

<div class="errorbox">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      	<td class="msgicon" align="left" valign="top">
      	    <img src="/_cm/img/<?=$this->message['type']?>-large.png" border="0" />
      	</td>
      	<td>
      	    <div class="msgbody"><?=$this->message['body']?></div>
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
