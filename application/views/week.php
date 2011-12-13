<?php
/**
 * 
 * We Love Difference - Points of view
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */
 
 if (isset($week)) {

if ($week->visibility_date <= date('Y-m-d')) {
	//Closed week
	$week->is_open = false;
}

?><h1><span class="green"><?php echo strtoupper(lang($week->is_open ? 'open_week_alt' : 'week')).' '.$week->week; ?>:</span> <?php echo $week->title; ?></h1>
<p style="width:90%"><?php echo $week->description; ?></p>
<?php } 

$photo_been_posted = $this->session->userdata('week_photo_'.$week->week);
if ($week->is_open && !$photo_been_posted) {
?>
	<a href="#next-week" onclick="see.load_week('next-week-form');" class="abutton"><?php echo lang('send_your_photo'); ?></a><br /><br />
<?php } ?>

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