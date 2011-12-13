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


Class Model_photos extends CI_Model {
	
	private $fields_to_select = 'id, author_name, author_www, author_email, abstract, img_path, img_width, img_height, visible, likes_count, source_file, week_id';
	
	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * Aggiunge una foto per la settimana
	 * @param array $data
	 * @return int insert_id
	 */
	function add_photo($data) {
		if (isset($data['week_id'])) {
		
			//Get the highest priority for this week
			$result = $this->db->select_max('priority')
					 ->from('see_photos')
					 ->where('week_id', $data['week_id'])
					 ->get();
					 
			$data['priority'] = $result->row(0)->priority + 1;
		
			$data['visible'] = 0;
			$data['user_language'] = LANG;
			$data['upload_date'] = date('Y-m-d H:i:s');
			if($this->db->insert('see_photos', $data)) {
				return $this->db->insert_id();
			}
		}else return false;
	}
	
	/**
	 * Ritorna le foto visibili relative alla settimana richiesta
	 * @param int $week_id
	 * @return obj
	 */
	function get_week_visible_photos($week_id) {
		$query = $this->db->select($this->fields_to_select)
						  ->from('see_photos')
						  ->where('week_id', $week_id)
						  ->where('visible', 1)
						  ->order_by('priority', 'DESC')
						  ->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	/**
	 * Ritorna tutte le foto relative alla settimana richiesta (per admin)
	 * @param int $week_id
	 * @return obj
	 */
	function get_week_photos($week_id) {
		$query = $this->db->select($this->fields_to_select.', priority')
						  ->from('see_photos')
						  ->where('week_id', $week_id)
						  ->order_by('visible', 'ASC')
						  ->order_by('priority', 'DESC')
						  ->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	/**
	 * Ritorna una singola foto
	 * @param int $id
	 * @param string $fields Fields to select (SQL query)
	 * @return obj
	 */
	function get_photo($id, $fields=null) {
		$query = $this->db->select($fields?$fields:$this->fields_to_select)
						  ->from('see_photos')
						  ->where('id', $id)
						  ->limit(1)->get();
		if ($query->num_rows() > 0) {
			return $query->row(0);
		}
	}
	
	
	/**
	 * Ritorna la foto vincitrice della settimana
	 * @param int $week_id
	 * @param string $fields Fields to select (SQL query)
	 * @return obj
	 */
	function get_winner_photo_for_week($week_id, $fields=null) {
		$query = $this->db->select($fields?$fields:$this->fields_to_select)
						  ->from('see_photos')
						  ->where('week_id', $week_id)
						  ->order_by('likes_count', 'DESC')
						  ->limit(1)->get();
		if ($query->num_rows() > 0) {
			return $query->row(0);
		}
	}
	
	
	/**
	 * Elimina una singola foto (record + files)
	 * @param int $id
	 * @return bool
	 */
	function delete_photo($id) {
		if (!$id) return false;

		//Get photo details
		$info = $this->get_photo($id, 'img_path, source_file');
		
		//Delete the photo and the original file
		unlink(FCPATH . $info->img_path);
		unlink(FCPATH . $info->source_file);

		//Delete record
		return $this->db->where('id', $id)->delete('see_photos');
	}
	
	/**
	 * Approva una foto
	 * @param int $id
	 * @return bool success
	 */
	function approve_photo($id) {
		return $this->db->where('id', $id)->update('see_photos', array('visible' => 1));
	}
	
	/**
	 * Aggiunge un like alla foto
	 * @param int $id
	 * @return bool success
	 */
	function add_like_to_photo($id) {
		$this->load->model('model_likes');
		
		//We need to check if the user can likes this photo
		if ($this->model_likes->can_like_photo($id)) {
			$ok = $this->db->where('id', $id)
						   ->set('likes_count', 'likes_count+1', false) //prevent escape
						   ->update('see_photos');
			if ($ok) {
				//Block like for this photo/user
				$this->model_likes->block_like_for_photo($id);
				return true;
			}
		
		}else return false;
		
	}

	/**
	 * Cambia la priorità di una foto
	 * @param int $photo_id
	 * @param +|-|int $value
	 */
	function change_priority_for_photo($photo_id='', $value='+') {
		if ($photo_id == '') return;
		
		$this->db->where('id', $photo_id);
		if ($value == '+' || $value == '-') {
			$this->db->set('priority','priority'.$value.'1', false);
		}else{
			$this->db->set('priority', (int)$value);
		}
					    
		return $this->db->update('see_photos');
	}
	
	function uid_can_send_photo($uid=null, $week=null) {
		if ($uid && $week) {
			$count = $this->db->from('see_photos')
							  ->where('uid', $uid)
							  ->where('week_id', $week)
							  ->count_all_results();
			if ($count == 0) return true;
		}
		return false;
	}
	
	function get_all_users() {
		$query = $this->db->distinct()
						  ->select('author_name, author_email, user_language')
						  ->from('see_photos')
						  ->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
}
