<?php



echo form_open('anana/login', array('style' => 'width:450px'));

?><h2>Amministrazione</h2><?php

echo '<strong>'.(isset($message) ? $message : '').'</strong>';

echo form_label('Username', 'username').form_input('username').'<div class="clear"></div><br /><br />';
echo form_label('Password', 'password').form_password('password').'<div class="clear"></div><br /><br />';

echo form_submit('submit', 'Login');
echo form_close();
