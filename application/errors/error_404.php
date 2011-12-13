<html>
<head>
<title>404 Page Not Found</title>
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
padding:			20px 20px 16px 20px;
}

#welove  {
border:				#FFF 1px solid;
background-color:	#FFF;
padding:			20px 20px 16px 20px;
font-size: 			10px;
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
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		If you wish, <strong><a href="/">head back</a></strong> to start.
	</div>
	<div id="welove">WE LOVE DIFFERENCE &copy; <?php echo date('Y'); ?></div>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23635657-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>