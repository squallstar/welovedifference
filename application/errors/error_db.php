<html>
<head>
<title>Error</title>
<style type="text/css">

body {
background-color:	#fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			12px;
color:				#333;
}

#content  {
border:				#999 1px solid;
background-color:	#8BD0BB;
padding:			20px 20px 12px 20px;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#000;
margin:				0 0 4px 0;
}
a {
	color: #555;
}
</style>
</head>
<body>
	<div id="content">
		<?php if (ENVIRONMENT == 'development') { ?>
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		<?php }else{ ?>
		<h1>The site is currently under maintenance.</h1>
		<p>Please try later.</p>
		<?php } ?>
	</div>
</body>
</html>