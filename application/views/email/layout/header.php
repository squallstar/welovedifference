<?php
$view_mail = $language == 'italian' ?
	'Se non leggi correttamente questo messaggio, clicca qui' :
	'Does this email look weird to you? Click here to view it in your browser';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php if ($is_web) { ?><link rel="shortcut icon" href="<?php echo site_url(); ?>widgets/favicon.gif" type="image/gif" /><?php } ?>
<title>We Love Difference</title>
</head>
<body bgcolor="#a9e1d1">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#a9e1d1">
	<tr>
		<td>
			<table width="520" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#a9e1d1">
			  <?php if ($is_web == false) { ?><tr>
			    <td height="40" align="center"><font face="Sans-serif" size="1" color="#000000"><a href="<?php echo site_url(); ?>email/dem/view/<?php echo $dem_id; ?>" style="color:#000000;text-decoration:none"><?php echo $view_mail; ?></a></font></td>
			  </tr><?php } ?>
			  <tr>
			    <td height="20" align="center">&nbsp;</a></td>
			  </tr>
			  <tr>
			    <td height="49" align="center" style="font-family:Sans-serif;"><a style="color:#000000;text-decoration:none" href="<?php echo site_url(); ?>"><img src="<?php echo site_url(); ?>widgets/logo_mail.gif" border="0" alt="WE LOVE DIFFERENCE"></a></td>
			  </tr>
			</table>
			
			<table width="520" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff" style="line-height:17px">
			  <tr>
			  	<td height="40" align="center">
			  		&nbsp;
			  	</td>
			  </tr>