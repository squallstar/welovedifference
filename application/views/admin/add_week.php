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

echo '<strong>'.$this->session->flashdata('message').'</strong>';

echo form_open(null, array('style'=>'width:550px'));

?><h2>Aggiungi una settimana</h2><br /><?php

echo form_hidden('act', '1');

echo form_label('Titolo', 'title').form_input('title').'<div class="clear"></div><br /><br />';
echo form_label('Descrizione', 'description').form_textarea('description').'<div class="clear"></div><br /><hr /><br />';
echo form_label('Title (ENGLISH)', 'title_en').form_input('title_en').'<div class="clear"></div><br /><br />';
echo form_label('Description (ENGLISH)', 'description_en').form_textarea('description_en').'<div class="clear"></div><br /><hr /><br />';
echo form_label('Data di pubblicazione', 'publish_date').form_input('publish_date', date('d/m/Y')).'<div class="clear"></div><br /><br />';

echo form_submit('submit', 'Aggiungi');
echo form_close();