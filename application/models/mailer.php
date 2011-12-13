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
 
/*
PROCEDIMENTO PER AGGIUNGERE UNA NUOVA MAIL:
 1) creare un nuovo metodo nel model mailer.php
 2) creare un nuovo ramo if nel metodo view del controller Dem
 3) Nella classe Anana, metodo send_mail aggiungere all'array $dems la nuova dem
 4) Nello stesso metodo aggiungere un case nello switch di invio

*/


Class Mailer extends CI_Model {

	private $web = false;

	public function __construct() {
		parent::__construct();
		$this->load->library('parser');
	}

	/**
	 * Imposta se è un layout web o mail
	 */
	public function set_web($bool=true) {
		$this->web = $bool;
		return $this;
	}

	/**
	 * Ottiene una chiave univoca per la stringa passata
	 * @param string $id
	 * @return string
	 */
	public function get_key($id='') {
		if ($id != '') return substr(md5('WLD_mailkey_'.$id), 0, 4);
		else return false;
	}

	/**
	 * Ottiene il layout di una mail
	 */
	public function _get_layout($content, $dem_id, $language) {
		return $this->load->view('email/layout/header',
					array(
						'dem_id' => $dem_id,
						'is_web' => $this->web,
						'language'	=> $language
					),
					true  //return instead echoing
				).
			   $content .
			   $this->load->view('email/layout/footer',
		   			array(
		   				'dem_id' => $dem_id,
		   				'is_web' => $this->web,
						'language'	=> $language
		   			),
		   			true //return instead echoing
			   );
	}

	/**
	 * DEM di invito generico nel sito
	 */
	public function DEM_invito($language='italian') {
		if ($language == '') $language = LANG;
		$content = $this->parser->parse('email/dem/'.$language.'-invito', array(), true);
		return $this->_get_layout($content, 'invite/', $language);
	}

	/**
	 * DEM di addio! :(
	 */
	public function DEM_goodbye($language='italian') {
		if ($language == '') $language = LANG;
		$content = $this->parser->parse('email/dem/'.$language.'-goodbye', array(), true);
		return $this->_get_layout($content, 'goodbye/', $language);
	}

	/**
	 * DEM invito iniziale per chi ci ha aiutato nella prima settimana
	 */
	public function DEM_thank_you_invite($name=null, $language='italian') {
		$content = $this->parser->parse('email/dem/'.$language.'-thank_you_invito', array(
				'name'		=> $name ? $name : lang('Ciao')
			),true
		);
		return $this->_get_layout($content, 'thank-you-invite/'.urlencode($name), $language);
	}

	/**
	 * DEM open weeks
	 */
	public function DEM_open_weeks($name=null, $language='italian') {
		$content = $this->parser->parse('email/dem/'.$language.'-open_weeks', array(
				'name'		=> $name ? $name : ''
			),true
		);
		return $this->_get_layout($content, 'open-weeks/'.urlencode($name), $language);
	}
	
	/**
	 * DEM invito iniziale per chi ci ha aiutato nella prima settimana
	 */
	public function DEM_news($name=null, $language='italian') {
		$content = $this->parser->parse('email/dem/'.$language.'-news', array(
				'name'		=> $name ? $name : lang('Ciao')
			),true
		);
		return $this->_get_layout($content, 'news/'.urlencode($name), $language);
	}

	/**
	 * Mail quando una foto viene approvata dal pannello di amministrazione
	 */
	public function photo_approved($name=null, $language='italian') {
		if ($language == '') $language = LANG;
		$content = $this->parser->parse('email/'.$language.'-photo_approved', array(
				'name'		=> $name
			),true
		);
		return $this->_get_layout($content, 'photo-approved/'.urlencode($name), $language);
	}

	/**
	 * Mail quando una foto viene rifiutata dal pannello di amministrazione
	 */
	public function photo_rejected($name=null, $language='italian') {
		if ($language == '') $language = LANG;
		$content = $this->parser->parse('email/'.$language.'-photo_rejected', array(
				'name'		=> $name
			),true
		);
		return $this->_get_layout($content, 'photo-rejected/'.urlencode($name), $language);
	}

	/**
	 * Mail con scritti i likes di una foto
	 */
	public function likes_count($params=null, $language='italian') {
		if ($language == '') $language = LANG;
		$content = $this->parser->parse('email/'.$language.'-likes_count', $params,true);
		return $this->_get_layout($content, 'likes-count/'.$params['id'].'-'.$this->get_key($params['id']), $language);
	}

	/**
	 * Mail che avvisa di una foto pubblicata
	 */
	public function photo_published($params=null, $language='italian') {
		if ($language == '') $language = LANG;
		$content = $this->parser->parse('email/'.$language.'-photo_published', $params,true);
		return $this->_get_layout($content, 'photo-published/'.$params['id'].'-'.$this->get_key($params['id']), $language);
	}
	
	/**
	 * Mail che avvisa del tema della settimana
	 */
	public function photo_themeweek($params=null, $language='italian') {
		if ($language == '') $language = LANG;
		
		$this->load->model('model_weeks');
		$next_week = $this->model_weeks->get_next_week();
		
		$content = $this->parser->parse('email/'.$language.'-photo_themeweek', array('name'	=> $params, 'tema' => $next_week->title, 'descrizione' => $next_week->description),true);
		return $this->_get_layout($content, 'theme-week/'.urlencode($params), $language);
	}

	/**
	 * Mail che avvisa di il vincitore di una foto
	 */
	public function photo_winner($params=null, $language='italian') {
		if ($language == '') $language = LANG;
		$content = $this->parser->parse('email/'.$language.'-photo_winner', $params,true);
		return $this->_get_layout($content, 'photo-winner/'.$params['id'].'-'.$this->get_key($params['id']), $language);
	}

}