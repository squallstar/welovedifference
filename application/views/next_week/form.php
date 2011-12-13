<div class="the_next_week">

	<?php
	echo form_open_multipart('week/next', array(
		'id'		=> 'next_week_form',
		'onsubmit'	=> 'return form_send();',
		'style'		=> 'width:650px'
	)); ?>

	<?php echo form_hidden('act', '1'); ?>
	
	<h2><?php echo lang('send_your_photo'); ?></h2>
	
	<div class="separator_line"></div>
	
	<div class="padding_box">
		<h3 class="week_title"><?php echo lang($next_week->is_open ? 'open_theme_week_is' : 'theme_week_is'); ?>: <span class="green"><?php echo $next_week->title; ?></span></h3>
		<p class="week_description"><?php echo str_replace(array('<br />', '<br>', '<br/>'), ' ', $next_week->description); ?></p>
	</div>
	
	<?php echo form_label(lang('your_name').'*', 'guest_name').form_input(array('name'=>'guest_name', 'class'=>'guest_name', 'maxlength'=>64)); ?>
	<div class="caption">(*<?php echo lang('mandatory'); ?>)</div>
	<div class="clear"></div><br /><br />
	
	<?php echo form_label(lang('your_email_address').'*', 'guest_email').form_input(array('name'=>'guest_email', 'maxlength'=>128)); ?>
	<div class="caption">(<?php echo lang('mandatory').' - '.lang('never_share_email'); ?>)</div>
	<div class="clear"></div><br /><br />
	
	<?php echo form_label(lang('your_web_address'), 'guest_www').form_input(array('name'=>'guest_www', 'maxlength'=>255)); ?>
	<div class="caption">(<?php echo lang('optional').' - '.lang('your_web_address_info'); ?>)</div>
	<div class="clear"></div><br />
	
	<hr />
	
	<?php echo form_label(lang('photo_to_publish'), 'the_picture').form_upload('the_picture'); ?>
	<br /><br />
	
	<?php echo form_label(lang('your_photo_description'), 'abstract').form_input(array('name'=>'abstract', 'maxlength'=>64)); ?>
	<div class="caption">(<?php echo lang('optional').' - '.lang('please_write_in_english'); ?>)</div>
	<div class="clear"></div><br />
	
	<hr />
	
	<div class="privacy"><?php echo str_replace(
	'{{CC}}',
	'<a href="http://creativecommons.org/licenses/by-nc-nd/3.0/" ref="external nofollow" target="_blank">Creative Commons</a>',
	lang('privacy_text'));
	?></div><br />
	
	<p class="careful"><?php echo lang($next_week->is_open ? 'open_send_be_careful' : 'send_be_careful'); ?></p><br />
	
	<div id="loading_photo" style="display:none"><p><img src="<?php echo base_url(); ?>widgets/loader_snake.gif" border="0" alt="" align="absmiddle"/> <?php echo lang('sending...'); ?></p></div>
	
	<?php echo form_submit('send', lang('send')); ?>
	<?php echo form_close(); ?>

</div>
<br /><br /><br />&nbsp;

<script type="text/javascript">
function form_send() {
	var error = false;
	if(!$('input[name="guest_name"]').val()) {
		error = '<?php echo lang('form_error_name'); ?>';
	}else if (!see.email_valid($('input[name="guest_email"]').val())) {
		error = '<?php echo lang('form_error_email'); ?>';
	}else if(!$('input[name="the_picture"]').val()) {
		error = '<?php echo lang('form_error_photo'); ?>';
	}
	
	
	
	if (error) {
		alert(error);
		return false;
	}else{
		$('input[type=submit]').fadeOut(200, function() {
			$('#loading_photo').fadeIn(200);
			$('input').attr('readonly', true).attr('disabled', true);
		});
		return true;
	}	
}
</script>