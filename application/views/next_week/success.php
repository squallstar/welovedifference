<?php if (LANG == 'italian') { ?>

<div class="stdbox">
	<h1><?php echo $guest_name; ?>, ecco la foto che ci stai inviando:</h1>
	<hr />
	<p class="bigger lighter" style="text-align:center">
		Manca solo un passo e la tua foto sar&agrave; pubblicata.<br />
		Confermaci la tua foto premendo il pulsante sotto!</p>
	
	<!--<h2><?php echo lang('photo_you_sent'); ?>:</h2>-->
	<img src="<?php echo base_url().$photo['src']; ?>" border="0" alt="" class="upl_photo"/>
	<div style="margin:0 auto">
		<a style="margin: 0 auto;" class="abutton" href="<?php echo site_url('?publish=1'); ?>">Pubblica la mia foto</a>
	</div>
	<br />
	<a class="link delete_photo" onclick="return confirm('<?php echo lang('really_delete_photo'); ?>');" href="<?php echo base_url(); ?>week/next/delete-my-photo"><?php echo lang('delete_this_photo'); ?></a><div class="clear"></div><br /><br />

</div>

<?php }else if (LANG == 'english') { ?>

<div class="stdbox">
	<h1>Dear <?php echo $guest_name; ?>, here is the photo you're sending us:</h1>
	<hr />
	<p class="bigger lighter" style="text-align:center">
		Thanks for sending us this wonderful picture.<br />
		You're a step ahead, just confirm the photo you are sending us by clicking the button below!</p>
	
	<!--<h2><?php echo lang('photo_you_sent'); ?>:</h2>-->
	<img src="<?php echo base_url().$photo['src']; ?>" border="0" alt="" class="upl_photo"/>
	<div style="margin:0 auto">
		<a style="margin: 0 auto;" class="abutton" href="<?php echo site_url('?publish=1'); ?>">Publish my photo</a>
	</div>
	<br />
	
	<a class="link delete_photo" onclick="return confirm('<?php echo lang('really_delete_photo'); ?>');" href="<?php echo base_url(); ?>week/next/delete-my-photo"><?php echo lang('delete_this_photo'); ?></a><div class="clear"></div><br /><br />

</div>


<?php } ?>

<br /><br />