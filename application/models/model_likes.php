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


	Class Model_likes extends CI_Model {
		
		private $use_database = FALSE;
		
		function __construct() {
			parent::__construct();
			$this->load->database();
			$this->load->model('model_auth');
		}
		
		/**
		 * Controlla se un utente può fare il like ad una foto
		 * @param int $photo_id
		 * @return bool
		 */
		function can_like_photo($photo_id) {
			if ($this->use_database) {
				if ($this->count_likes_for_photo_using_fingerprint($photo_id, true) < 1) return true;
				else return false;	
			}else{	
				//Session like
				$is_liked = $this->session->userdata('is_liked');
				return isset($is_liked[$photo_id]) ? false : true;
			}
			
		}
		
		/**
		 * Conta il numero di like di una foto per un determinato fingerprint
		 * @param int $photo_id
		 * @param bool $user_limited sceglie se limitare il count per l'utente corrente
		 * @return bool
		 */
		function count_likes_for_photo_using_fingerprint($photo_id, $user_limited=false) {
			if ($photo_id) {
				$this->db->from('see_likes')
						 ->where('photo_id', (int)$photo_id);
								
				if ($user_limited) {
					$this->db->where('ip_address', $this->input->ip_address())
							 ->where('user_agent', $this->model_auth->get_my_fingerprint());
				}
				
				return $this->db->count_all_results();
			}else return true; //Returns true to stop nested functions on the same model
		}
		
		/**
		 * Blocca la possibilita' di fare il like per il fingerprint corrente
		 */
		function block_like_for_photo($photo_id) {			
			if ($photo_id) {
			
				//Session block
				$is_liked = $this->session->userdata('is_liked');
				$is_liked[$photo_id] = true;
				$this->session->set_userdata('is_liked', $is_liked);
				
				if ($this->use_database) {
				
					//Db Block - DISABLED
					$data = array(
						'user_agent'		=> $this->model_auth->get_my_fingerprint(),
						'ip_address'		=> $this->input->ip_address(),
						'date_time'			=> date('Y-m-d H:i:s'),
						'photo_id'			=> (int)$photo_id
					);
					return $this->db->insert('see_likes', $data);
				
				}else{
					return true;
				}
			}
		}
		
		/**
		 * Elimina i likes dell'utente
		 * @return bool
		 */
		function flush_my_likes() {
			return $this->db->delete('see_likes', array(
				'user_agent'	=> $this->model_auth->get_my_fingerprint(),
				'ip_address'	=> $this->input->ip_address(),
			));
		}
	}