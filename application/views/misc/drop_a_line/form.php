<h1><?php echo lang('drop_title'); ?></h1>
<p><?php echo lang('drop_abstract'); ?></p>

<?php
	echo form_open(null, array(
		'id'		=> 'next_week_form',
		'onsubmit'	=> 'return form_send();',
		'style'		=> 'width:650px'
	)); ?>

	<?php echo form_hidden('act', '1'); ?>
	
	
	<?php echo form_label(lang('your_name').'*', 'uname').form_input(array('name'=>'uname', 'required'=>true, 'maxlength'=>64)); ?>
	<div class="caption">(*<?php echo lang('mandatory'); ?>)</div>
	<div class="clear"></div><br /><br />
	
	<?php echo form_label(lang('your_email_address').'*', 'email').form_input(array('name'=>'email', 'maxlength'=>128)); ?>
	<div class="caption">(<?php echo lang('mandatory'); ?>)</div>
	<div class="clear"></div><br /><br />
	
	<?php echo form_label(lang('your_message').'*', 'message').form_textarea(array('name'=>'message', 'required'=>true)); ?>
	<div class="caption">(*<?php echo lang('mandatory'); ?>)</div>
	<div class="clear"></div><br /><br />
	
	
	<?php echo form_submit('send', lang('send')); ?>
	<?php echo form_close(); ?>
	
	
<script type="text/javascript">
	function form_send() {
		var error = false;
		if(!$('input[name="uname"]').val()) {
			error = '<?php echo lang('form_error_name'); ?>';
		}else if (!see.email_valid($('input[name="email"]').val())) {
			error = '<?php echo lang('form_error_email'); ?>';
		}
		
		if (error) {
			alert(error);
			return false;
		}else{
			see.forms.drop.send();
			return false;
		}	
	}
</script>