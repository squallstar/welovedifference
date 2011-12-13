<?php if (LANG == 'italian') { ?>
<div id="the_white_thing">
	<div class="the_center">
		<h1>Benvenuto/a su <strong>We Love Difference</strong>!</h1>
		<h2>Guardiamo il mondo con gli occhi degli altri.</h2>
		<p>Ogni settimana un <strong>tema</strong> da immortalare. Le regole? Nessuna! Sentiti libero di <strong>fotografare</strong>, <strong>disegnare</strong>, immaginare e condividere: partecipa inviando la tua foto entro <strong style="text-decoration:underline">domenica sera</strong>!</p>

		<div class="buttons">
			<a href="#how-it-works" class="btn" onclick="see.fade_intro().load_page('misc/howitworks');">Come funziona</a>
			<a href="#" class="btn last" onclick="see.fade_intro().load_week(<?php echo isset($week->week) ? $week->week : ''; ?>);">Salta &#187;</a>
			<div class="clear"></div>
		</div>

	</div>
</div>
<?php }else{ ?>
<div id="the_white_thing">
	<div class="the_center">
		<h1>Welcome on <strong>We Love Difference</strong>!</h1>
		<h2>We love <strong>people</strong>'s points of view and their <strong>photos</strong>.</h2>
		<p>Each week a <strong>theme</strong> to represent. Rules? No rules! Be free to express yourself with photos, illustrations and much more... and share:
		we want your photo before <strong style="text-decoration:underline">sunday night</strong>!</p>

		<div class="buttons">
			<a href="#how-it-works" class="btn" onclick="see.fade_intro().load_page('misc/howitworks');">How it works</a>
			<a href="#" class="btn last" onclick="see.fade_intro().load_week(<?php echo isset($week->week) ? $week->week : ''; ?>);">Skip &#187;</a>
			<div class="clear"></div>
		</div>

	</div>
</div>
<?php } ?>