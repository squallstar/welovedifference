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

class Welcome extends SEE_Controller {

	/*
	 * URI :  change-language/
	 */
	function change_language($lang) {
		$this->_setLanguage($lang);
		redirect('/welcome');
	}

	/**
	 * Homepage del sito
	 */
	function index() {
		$this->load->model(array('model_weeks', 'model_photos', 'model_auth'));
		$this->load->helper('xhtml');

		if (ENVIRONMENT == 'development' && !$this->model_auth->is_logged_in()) {
			show_error('Only available for development purposes. Please log in first', 400);
		}

		//Get all the published weeks + next week
		$this->view['weeks'] = $this->model_weeks->get_weeks();
		$this->view['next_week'] = $this->model_weeks->get_next_week();

		//The current week
		$keys = array_keys($this->view['weeks']);
		if ($keys) $this->view['week'] = $this->view['weeks'][$keys[0]];
		
		//Remove the prev cookies (useless)
		$this->session->unset_userdata('winner_photo_'.$this->view['week']->week);
		$this->session->unset_userdata('week_photo_'.$this->view['week']->week);

		//Last week (week_id)
		$last_week = $this->view['weeks'][$keys[1]]->week;

		//Publish my photo
		if ($this->input->get('publish'))
		{
			$my_photo = $this->session->userdata('publish_photo');
			if ($my_photo)
			{
				//Approvo la foto in automatico
				$this->model_photos->approve_photo($my_photo);
				$this->session->unset_userdata('publish_photo');
				
				$this->view['published_photo'] = $this->model_photos->get_photo($my_photo);
				
				//E invio la mail
				$this->load->library(array('email', 'parser'));
				$this->load->model('mailer');
				$this->email->from($this->config->item('website_email'), 'We Love Difference');
				//Sets the user email
				$photo = $this->view['published_photo'];
				$this->email->to($photo->author_email);
				
				//User language and name
				$language = isset($photo->user_language) ? $photo->user_language : LANG;
				$name = $photo->author_name;
				
				
				//Send the "photo approved" mail to the user
				$this->email->subject($language == 'italian' ? 'La tua foto è stata pubblicata!' : 'Your photo has been published!');
				$this->email->message( $this->mailer->set_web(false)->photo_approved($name, $language) );
				$this->email->send();
				
				//Finished!
				
				
				//Current week is open
				$next_week = $this->view['next_week'];
				if (isset($next_week) && $next_week->is_open) {
					$this->view['week'] = $next_week;
				}
				
				//Get current week photos
				if (isset($this->view['week'])) {
					$this->view['week_photos'] = $this->model_photos->get_week_visible_photos($this->view['week']->week);
				}
	
				$this->renderLayout('week');
				return;
				
				
			}
			
		}

		//Wednesday popup (photo winner)
		if (!$this->session->userdata('winner_photo_'.$last_week) && date('l') == 'Wednesday'
			&& date('Y-m-d', strtotime("last Monday")) == $this->view['weeks'][$keys[0]]->visibility_date
		) {
		
			/*
			Il vincitore va mostrato solo il mercoledì successivo alla chiusura di una settimana
			Qui sopra controllo se l'ultimo lunedì è la data di scadenza dell'ultima settimana, e se 
			coincidono allora mostro il vincitore.
			FIX: 2011-08-27
			*/
		
			//Sets the data to no repeat this flow
			$this->session->set_userdata('winner_photo_'.$last_week, true);
		
			//Get the winner photo
			$this->view['winner_photo'] = $this->model_photos->get_winner_photo_for_week($this->view['weeks'][$keys[0]]->week);
		
			$this->view['load_winner'] = true;
			
			//Loads the empty layout
			$this->renderLayout();
		
		//Is this the first visit here? -- CURRENTLY DISABLED WITH FALSE TRIGGER
		}else if (!$this->session->userdata('recurrent_visitor') && FALSE) {

			//First visit - sets the session to display this thing once
			$this->session->set_userdata('recurrent_visitor', true);

			//Loads the intro
			$this->view['load_intro'] = true;

			//Loads the empty layout
			$this->renderLayout();

		}else{

			//Current week is open
			$next_week = $this->view['next_week'];
			if (isset($next_week) && $next_week->is_open) {
				$this->view['week'] = $next_week;
			}
			
			//Get current week photos
			if (isset($this->view['week'])) {
				$this->view['week_photos'] = $this->model_photos->get_week_visible_photos($this->view['week']->week);
			}

			$this->renderLayout('week');
		}

	}
	
	function week($id, $name='photo', $photo_id=null) {
	
		$this->load->model(array('model_weeks', 'model_photos', 'model_auth'));
		$this->load->helper('xhtml');
	
		//Get all the published weeks + next week
		$this->view['weeks'] = $this->model_weeks->get_weeks();
		$this->view['next_week'] = $this->model_weeks->get_next_week();

		//The selected week
		foreach ($this->view['weeks'] as $week) {
			if ($week->week == $id) {
				$this->view['week'] = $week;
				break;
			}
		}
		
		$next_week = $this->view['next_week'];
		if ($next_week->week == $id) {
			$this->view['week'] = $next_week;
			$week = $next_week;
		}
		
		//Get selected week photos
		if (isset($this->view['week'])) {
			$this->view['title'] = $week->title . ' &bull; '.lang('week').' '.$week->week;
			$this->view['meta_description'] = $week->description;
			$this->view['week_photos'] = $this->model_photos->get_week_visible_photos($this->view['week']->week);
			$this->view['selected_photo'] = $photo_id;
			$this->view['selected_week'] = $week->week;
		}

		$this->renderLayout('week');
	}
}
