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
 
Class Misc extends SEE_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->language('misc', LANG);
	}
	
	function howitworks() {
		$this->render('misc/how_it_works/'.LANG);
	}
	
	function credits() {
		if (CACHE) $this->output->cache(180);
		$this->render('misc/credits');
	}
	
	function drop() {
		$this->_checkFireLinking();
		
		if ($this->input->post('name')) {
			
			$this->load->library(array('email', 'parser'));
			
			$this->email->from($this->config->item('website_email'), 'We Love Difference');
			$this->email->to($this->config->item('administrator_email')); 
			$this->email->cc($this->config->item('moderator_email')); 
			
			$this->email->subject('Someone dropped a line');
			
			//Loads the view via the parser
			$msg = $this->parser->parse('misc/drop_a_line/email_template', array(
				'name'	=> $this->input->post('name'),
				'email'	=> $this->input->post('email'),
				'message'	=> $this->input->post('message')
				),true
			); 
			
			$this->email->message($msg);	
			
			//Sends email only when not in development
			$email_sent = ENVIRONMENT != 'development' ? $this->email->send() : true;
			
			if ($email_sent) {
				$this->render('misc/drop_a_line/success');
			}
			
			
		}else{
			//Ajax request - simply shows the form
			$this->load->helper('form');
			$this->render('misc/drop_a_line/form');
		}
		
		
	}
	
}