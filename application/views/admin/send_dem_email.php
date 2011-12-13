<div class="dem_sender">
<?php



echo form_open();

?><h2>Invio e-mail</h2><?php

if ($this->session->flashdata('message')) {
	echo '<p><strong>'.$this->session->flashdata('message').'</strong></p>';
}

$languages = array(
	'italian'	=> 'Italiano',
	'english'	=> 'English'
);

echo form_fieldset('Seleziona un template e la lingua');
echo form_dropdown('dem', $dems, set_value('dem')).' > ';
echo form_dropdown('language', $languages, set_value(LANG));
echo form_fieldset_close('<br />');

echo form_fieldset('Inserisci i destinatari').'<br /><br /><div style="float:left;width:320px" class="name_label">1) NOME</div><div style="float:left;width:300px">2) INDIRIZZO E-MAIL</div><div class="clear"></div><br /><br />';
for ($i=0;$i<6;$i++) {
	echo form_input(array('name'=>'name[]', 'class' => 'the_name', 'style'=>'margin-right:10px', 'placeholder'=>'Nome')).
		 form_input(array('name'=>'email[]', 'placeholder'=>'Indirizzo e-mail')).'<div class="clear"></div><br />';
}
echo form_fieldset_close('<br /><br />');

echo form_checkbox(array('name'=>'act', 'onchange'=>'check_checkbox();'), '1').'Sono sicuro di voler inviare la mail ai destinatari da me inseriti<br /><br />';
echo form_submit(array('name'=>'submit', 'disabled'=>'disabled', 'onclick' => 'return confirm(\'Sei davvero sicuro di inviare la mail con il template \'+$(\'select[name=dem]\').val()+\'?\'); '), 'Invia');
echo form_close();


?>

<script type="text/javascript">
function check_checkbox() {
	if ($('input[name=act]').attr('checked')) {
		$('input[type=submit]').attr('disabled', false);
	}else $('input[type=submit]').attr('disabled', true);
}

function check_visibility() {
	if ($('select[name=dem]').val() == 'invite') {
		//Toggle off the names
		$('.the_name, .name_label').fadeOut(300);
	}else{
		//Toggle on the names
		$('.the_name, .name_label').fadeIn(300);
	}
}

$(document).ready(function() {
	check_visibility();
	$('select[name=dem]').click(function() {
		check_visibility();
	});
});

</script>

</div>
