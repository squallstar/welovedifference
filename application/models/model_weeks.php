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

Class Model_weeks extends CI_Model {

	private $title = 'title';
	private $description = 'description';

	function __construct() {
		$this->load->database();
		if (LANG == 'english') {
			$this->title .= '_en AS title';
			$this->description .= '_en AS description';
		}
	}

	/**
	 * Aggiunge una settimana
	 */
	function add_week($data, $is_open = TRUE)
	{
		//Select highest week number
		$query = $this->db->select('week')
						  ->from('see_weeks')
						  ->order_by('week', 'DESC')
						  ->limit(1)->get();

		if ($query->num_rows() > 0) {
			$row = $query->row(0);
			$data['week'] = $row->week + 1;
		}else $data['week'] = 1;

		if ($is_open)
		{
			$data['is_open'] = 1;
		}

		return $this->db->insert('see_weeks', $data);
	}

	/**
	 * Ritorna una singola settimana
	 * @return obj
	 */
	function get_week($week_num=null)
	{
		if (!$week_num) return false;
		$query = $this->db->select('id, '.$this->title.', '.$this->description.', visibility_date, is_open')
				 		  ->from('see_weeks')
						  ->where('week', $week_num)
						  ->limit(1)->get();

		if ($query->num_rows() > 0) {
			$row = $query->row(0);
			$row->week = $week_num;
			return $row;
		}else return false;
	}

	/**
	 * Ritorna una singola settimana
	 * @return obj
	 */
	function get_week_by_real_id($id=null) {
		if (!$id) return false;
		$query = $this->db->select('week, '.$this->title.', '.$this->description.', visibility_date, is_open')
				 		  ->from('see_weeks')
						  ->where('id', $id)
						  ->limit(1)->get();

		if ($query->num_rows() > 0) {
			$row = $query->row(0);
			$row->id = $id;
			return $row;
		}else return false;
	}

	/**
	 * Ritorna tutte le settimane pubblicate
	 * @return array
	 */
	function get_weeks() {
		return $this->db->select('id, week, '.$this->title.', '.$this->description.', visibility_date, is_open')
						->from('see_weeks')
						->where('visibility_date <=', date('Y-m-d'))
						->order_by('visibility_date', 'DESC')
						->get()->result();
	}

	/**
	 * Ritorna tutte le settimane
	 * @return array
	 */
	function get_all_weeks() {
		return $this->db->select('id, week, '.$this->title.', '.$this->description.', visibility_date, likes_sent, published_sent, winner_sent, is_open')
				 		->from('see_weeks')
				 		->order_by('visibility_date', 'DESC')
				 		->get()->result();
	}

	/**
	 * Ritorna la prossima settimana (non pubblicata)
	 * @return object
	 */
	function get_next_week() {
		$query = $this->db->select('id, week, '.$this->title.', '.$this->description.', visibility_date, is_open')
						  ->from('see_weeks')
						  ->where('visibility_date >', date('Y-m-d'))
						  ->order_by('visibility_date', 'ASC')
						  ->limit(1)->get();

		if ($query->num_rows() > 0) {
			$row = $query->row(0);
			$row->deadline = date("Y-m-d", strtotime($row->visibility_date)-86400); //Yesterday
			return $row;
		}
	}

	/**
	 * Elimina una settimana
	 */
	function delete_week($id) {
		return $this->db->delete('see_weeks', array('id' => $id));
	}

	/**
	 * Controlla se una settimana ha foto da approvare
	 */
	function get_pending_photos_count($week_id) {
		return $this->db->from('see_photos')
						->where('visible', 0)
						->where('week_id', $week_id)
						->count_all_results();
	}

	/**
	 * Imposta la mail inviata per i likes
	 * @param int $week_id
	 * @return bool success
	 */
	function week_likes_sent($week_id) {
		return $this->db->where('week', $week_id)->update('see_weeks', array('likes_sent' => 1));
	}
	
	/**
	 * Imposta la mail inviata per la settimana pubblicata
	 * @param int $week_id
	 * @return bool success
	 */
	function week_published_sent($week_id) {
		return $this->db->where('week', $week_id)->update('see_weeks', array('published_sent' => 1));
	}
	
	/**
	 * Imposta la mail inviata per la settimana pubblicata
	 * @param int $week_id
	 * @return bool success
	 */
	function week_winner_sent($week_id) {
		return $this->db->where('week', $week_id)->update('see_weeks', array('winner_sent' => 1));
	}
	
	/**
	 * Apre una settimana
	 * @param int $week_id
	 * @return bool success
	 */
	function open_week($week_id) {
		return $this->db->where('week', $week_id)->update('see_weeks', array('is_open' => 1));
	}

}