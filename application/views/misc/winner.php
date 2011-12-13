<?php if (LANG == 'italian') { ?>
<div id="the_white_thing" style="position:absolute" onclick="see.fade_intro().load_week(<?php echo isset($week->week) ? $week->week : ''; ?>);">
	<div class="the_center" style="top:50px;margin-top:0;position:absolute">
		<h2 style="font-size:23px;font-weight:500">La foto pi&ugrave; votata della scorsa settimana &egrave;...<br /></h2>
		
		<p style="text-align:center">
			<img src="<?php echo base_url().$winner_photo->img_path; ?>" border="0" alt="<?php echo $winner_photo->abstract; ?>" />
		</p>
		<h2>...<em style="font-weight:500">"<?php echo $winner_photo->abstract ? $winner_photo->abstract : lang('untitled'); ?>"</em> (<?php echo $winner_photo->likes_count; ?> voti), di <strong style="font-weight:500"><a href="http://<?php echo $winner_photo->author_www; ?>" rel="external" target="_blank"><?php echo $winner_photo->author_name; ?></a></strong></h2>

		<h2>psst..! Sei su <strong style="font-weight:500">We Love Difference</strong>, ora guarda il mondo con gli occhi degli altri.</h2>
		<p>Ogni settimana un <strong>tema</strong> da immortalare. Le regole? Nessuna! Sentiti libero di <strong>fotografare</strong>, <strong>disegnare</strong>, immaginare e condividere: partecipa inviando la tua foto entro <strong style="text-decoration:underline">domenica sera</strong>!</p>

		<div>
			<a href="#" class="btn" style="float:none;margin:0 auto;" onclick="see.fade_intro().load_week(<?php echo isset($week->week) ? $week->week : ''; ?>);">Continua &#187;</a>
		</div>

	</div>
</div>
<?php }else{ ?>
<div id="the_white_thing" style="position:absolute" onclick="see.fade_intro().load_week(<?php echo isset($week->week) ? $week->week : ''; ?>);">
	<div class="the_center" style="top:50px;margin-top:0;position:absolute">
		
		<h2 style="font-size:23px;font-weight:500">And the winner of the last week is...<br /></h2>
		
		<p style="text-align:center">
			<img src="<?php echo base_url().$winner_photo->img_path; ?>" border="0" alt="<?php echo $winner_photo->abstract; ?>" />
		</p>
		<h2>...<em style="font-weight:500">"<?php echo $winner_photo->abstract ? $winner_photo->abstract : lang('untitled'); ?>"</em> (<?php echo $winner_photo->likes_count; ?> likes), by <strong style="font-weight:500"><a href="http://<?php echo $winner_photo->author_www; ?>" rel="external" target="_blank"><?php echo $winner_photo->author_name; ?></a></strong></h2>

		<h2>post..! You're on <strong style="font-weight:500;">We Love Difference</strong>! We love <strong>people</strong>'s and their <strong>photos</strong>.</h2>
		<p>Each week a <strong>theme</strong> to represent. Rules? No rules! Be free to express yourself with photos, illustrations and much more... and share:
		we want your photo before <strong style="text-decoration:underline">sunday night</strong>!</p>

		<div>
			<a href="#" class="btn" style="float:none;margin:0 auto;" onclick="see.fade_intro().load_week(<?php echo isset($week->week) ? $week->week : ''; ?>);">Skip &#187;</a>
		</div>

	</div>
</div>
<?php } ?>