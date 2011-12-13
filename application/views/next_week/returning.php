<div class="returning_nextw">
	<h1><?php echo str_replace('{NAME}', isset($photo['author']) ? ' '.$photo['author'] : '', lang('already_posted_title')); ?></h1>

	<img class="main" src="<?php echo base_url(); ?>widgets/vectors/nextweek_returning.png" border="0" alt="" />

	<?php if (LANG == 'italian') { ?>
	<p class="below_main">Però ci piace il tuo entusiasmo</p>
	<h2>GRAZIE!</h2>
	<?php }else{ ?>
	<p class="below_main">But we love your enthusiasm</p>
	<h2>THANKS!</h2>
	<?php } ?>


	<?php if (isset($photo)) { ?>

		<div class="returning_box">
			<h2><?php echo lang('photo_you_sent'); ?>:</h2>

			<div class="left">
			<img src="<?php echo base_url().$photo['src']; ?>" width="<?php echo round($photo['img_width']/2); ?>px" height="<?php echo round($photo['img_height']/2); ?>px" border="0" alt="" />
			</div>

			<div class="right">
				<p><?php echo lang('photo_title'); ?>: <?php echo $photo['abstract']; ?>
				<a class="link" onclick="return confirm('<?php echo lang('really_delete_photo'); ?>');" href="<?php echo base_url(); ?>week/next/delete-my-photo"><?php echo lang('delete_this_photo'); ?></a>
			</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
</div>

