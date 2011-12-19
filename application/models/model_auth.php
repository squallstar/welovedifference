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
 
Class model_auth extends CI_Model {
	
	private $fingerprint;

	private $_admin_user = 'admin';
	private $_admin_pwd  = 'password';
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
		
		//Try to generate a unique identifier for this user. Will be used linked to the IP address
		$this->fingerprint =  $this->input->user_agent() . ' - '
							 .$this->input->server('HTTP_ACCEPT_LANGUAGE') . ' - '
							  //.$_SERVER['HTTP_ACCEPT']
							 ;
		
	}
	
	public function get_my_fingerprint() {
		return $this->fingerprint;
	}
	
	public function is_logged_in() {
		return $this->session->userdata('logged_in') ? true : false;
	}
	
	public function login($username, $password) {
		//Currently is static!
		if ($this->_admin_user == $username && $this->_admin_pwd == $password) {
			$this->session->set_userdata('logged_in', 'admin');
			return true;
		}
	}
	
	public function logout() {
		$this->session->unset_userdata('logged_in');
		return true;
	}
	
	/**
	 * Blocca l'upload per la settimana scelta
	 */
	public function block_week($week_id, $photo_id) {
		if (!$week_id || !$photo_id) return;
		$this->db->insert('see_sessions', array(
			'ip_address'	=> $this->input->ip_address(),
			'user_agent'	=> $this->fingerprint,
			'week_id'		=> (int)$week_id,
			'photo_id'		=> (int)$photo_id,
			'date_time'		=> date('Y-m-d H:i:s')
		));
	}
	
	/**
	 * Controlla se l'utente può postare una foto per la settimana corrente
	 */
	public function week_is_blocked($week_id) {
		$query = $this->db->select('photo_id')->from('see_sessions')
						  ->where('week_id', $week_id)
						  ->where('ip_address', $this->input->ip_address())
						  ->where('user_agent', $this->fingerprint)
						  ->limit(1)->get();
		if ($query->num_rows() > 0) {
			$row = $query->row(0);
			return $row->photo_id;
		}else return false;
	}
	
	/**
	 * Elimina i blocchi dell'utente attuale
	 */
	public function flush_week_blocks() {
		$this->db->delete('see_sessions', array(
			'ip_address'	=> $this->input->ip_address(),
			'user_agent'	=> $this->fingerprint
		));
	}
	
}
