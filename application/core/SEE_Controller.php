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
 
Class SEE_Controller extends CI_Controller {
	
	public $view = array();
	
	public function __construct() {
		parent::__construct();
		
		$this->output->set_header('Content-Type: text/html; charset=utf-8');
		
		$this->_languageCheck();
	}
	
	protected function render($view) {
		$this->load->view($view, $this->view);
	}

	protected function renderLayout($view='') {
		$this->load->view('layout/header', $this->view);
		if ($view != '') $this->render($view);
		$this->load->view('layout/footer',  $this->view);
	}
	
	/**
	 * Controllo la lingua corrente o la setto
	 */
	private function _languageCheck() {
		//Force language via GET
		if ($this->input->post('set_language')) {
			$this->_setLanguage(substr($this->input->post('set_language'),0,2));
			return;
		}
		if (isset($this->force_language)) {
			$this->_setLanguage($this->force_language);
		}
		
		$lang = $this->session->userdata('lang');
		if (!$lang) {
			$this->_setLanguage(substr($this->input->server('HTTP_ACCEPT_LANGUAGE'),0,2));
		}else{
			$this->load->language('main', $lang);
			if (!defined('LANG')) {
				define('LANG', $lang);
			}
		}
		
	}
	
	protected function _checkFireLinking() {
		if (!$this->input->is_ajax_request()) {
			show_error('This service is protected from fire-linking. 
			Please <a href="'.base_url().'">head back</a> to start.', 405);
		}
	}
	
	/**
	 * Imposta la lingua del sito
	 */
	protected function _setLanguage($lang) {
		switch ($lang) {
			case 'it':
				$l = 'italian';
				break;
			case 'en':
			default:
				$l = 'english';
				break;
		}
		$this->session->set_userdata('lang', $l);
		$this->load->language('main', $l);
		if (!defined('LANG')) {
			define('LANG', $l);
		}
		
	}
	
}