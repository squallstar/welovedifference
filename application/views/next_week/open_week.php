<?php if (isset($week)) { ?><h1><span class="green"><?php echo strtoupper(lang('open_week')).' '.$week->week; ?>:</span> <?php echo $week->title; ?></h1>
<p><?php echo $week->description; ?></p><?php } ?>

OPEN WEEK

<div class="content clearfix">
	<?php
	if (isset($week_photos)) {
		$is_liked = $this->session->userdata('is_liked');
		foreach ($week_photos as $photo) {
			$photo->is_liked = isset($is_liked[$photo->id]) ? $is_liked[$photo->id] : false;
			echo print_photo($photo);
		}
	}
	?>
</div>
<div class="go_top">
	<span class="g_top" onclick="see.viewport.top();">&circ; <?php echo lang('go_top'); ?></span>
</div>
<script type="text/javascript">
$(document).ready(function() {
	see.load_lightbox();
	<?php echo isset($selected_photo) ? '$(\'.photo[data-photoid='.$selected_photo.'] .ltbx\').click();' : ''; ?>
});
</script>