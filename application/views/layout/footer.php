		</div>
	</div>
	<div class="clear"></div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.masonry.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.lightbox.min.js"></script>
<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var lang = '<?php echo LANG; ?>';
</script>
<?php if (!isset($admin) && ENVIRONMENT != 'development') { ?><script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-XXXXXXXX-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script><?php } ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/application.js?v=3"></script>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<!-- We did it in {elapsed_time}s -->
</body>
</html>