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
 
Class Anana extends SEE_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('model_weeks', 'model_photos', 'model_auth'));
		$this->view['admin'] = true;
		
	}
	
	function _check_login() {
		if (!$this->model_auth->is_logged_in()) {
			redirect('anana/login');
		}
	}
	
	function index() {
		if (!$this->model_auth->is_logged_in()) {
			redirect('anana/login');
		}else{
			redirect('anana/list_weeks');
		}
	}
	
	function login() {
		if ($this->model_auth->is_logged_in()) {
			redirect('anana/list_weeks');
		}
		
		if ($this->input->post('username')) {
			$done = $this->model_auth->login(
				$this->input->post('username'),
				$this->input->post('password')
			);
			if ($done) redirect('anana/list_weeks');
			else $this->view['message'] = 'Wrong username/password. Please try again.';
		}
		
		$this->load->helper('form');
		$this->view['disable_admin_menu'] = false;
		$this->renderLayout('admin/login');
	}
	
	function logout() {
		$this->model_auth->logout();
		redirect('anana/login');
	}
	
	

	function add_week() {
		$this->_check_login();
		if ($this->input->post('act')) {
			//Save a new week
			$publish = implode('-', array_reverse(explode('/', $this->input->post('publish_date'))));		

			$data = array(
				'title'				=> $this->input->post('title'),
				'description'		=> $this->input->post('description'),
				'title_en'			=> $this->input->post('title_en'),
				'description_en'	=> $this->input->post('description_en'),
				'visibility_date'	=> $publish
			);
			if ($this->model_weeks->add_week($data)) {
				redirect('anana/list_weeks');
			}
		}
		
		$this->load->helper('form');
		$this->renderLayout('admin/add_week');
	}
	
	function list_weeks() {
		$this->_check_login();
		$this->view['next_week'] = $this->model_weeks->get_next_week();
		
		$temp_weeks = $this->model_weeks->get_all_weeks();
		$weeks = array();
		foreach ($temp_weeks as $week) {
			$week->pending = $this->model_weeks->get_pending_photos_count($week->week);
			$weeks[]= $week;
		}
		
		
		$this->view['weeks'] = $weeks;
		
		$this->renderLayout('admin/list_weeks');
	}
	
	/**
	 * Eliminazione settimana
	 */
	function delete_week($id=null) {
		$this->_check_login();
		if ($id) {
			$week = $this->model_weeks->get_week_by_real_id($id);
			
			//Get week photos
			$photos = $this->model_photos->get_week_photos($week->week);
			
			//Delete all photos
			foreach ($photos as $photo) {
				$this->model_photos->delete_photo($photo->id);
			}
			
			if ($this->model_weeks->delete_week($id)) {
				$this->session->set_flashdata('message', 'Week has been deleted.');
				redirect('anana/list_weeks');
			}
		}
	}
	
	/**
	 * Dettaglio settimana con moderazione foto
	 */
	function week($num=null, $action=null, $affect_id=null) {
		$this->_check_login();
		if (!$num) show_error('Week not set.');
		
		if ($action == 'approve' || $action == 'delete') {
			//Loads the email class
			$this->load->library(array('email', 'parser'));
			$this->load->model('mailer');
			$this->email->from($this->config->item('website_email'), 'We Love Difference');
			
			//Sets the user email
			$photo = $this->model_photos->get_photo($affect_id, 'author_name, author_email, user_language');
			$this->email->to($photo->author_email);
			
			//User language and name
			$language = isset($photo->user_language) ? $photo->user_language : LANG;
			$name = $photo->author_name;
		}
		
		if ($action == 'approve') {
			$this->model_photos->approve_photo($affect_id);
			
			//Send the "photo approved" mail to the user
			$this->email->subject($language == 'italian' ? 'La tua foto è stata pubblicata!' : 'Your photo has been published!');
			$this->email->message( $this->mailer->set_web(false)->photo_approved($name, $language) );
			$this->email->send();
			
		}else if ($action == 'delete') {
			$this->model_photos->delete_photo($affect_id);
			
			//Send the "photo rejected" mail to the user
			$this->email->subject($language == 'italian' ? 'La tua foto è stata rifiutata' : 'Your photo has been rejected');
			$this->email->message( $this->mailer->set_web(false)->photo_rejected($name, $language) );
			$this->email->send();
			
		}else if ($action == 'priority-up') {
			$done = $this->model_photos->change_priority_for_photo($affect_id, '+');
			
		}else if ($action == 'priority-down') {
			$this->model_photos->change_priority_for_photo($affect_id, '-');
		}
		
		$this->view['week'] = $this->model_weeks->get_week($num);
		$this->view['week_photos'] = $this->model_photos->get_week_photos($num);
		$this->renderLayout('admin/week_photos');
	}
	
	/**
	 * Elimina i blocchi dell'utente in uso
	 */
	function flush_blocks() {
		$this->_check_login();
		
		$next_week = $this->model_weeks->get_next_week();
		
		//Delete database ip based blocks
		$this->model_auth->flush_week_blocks();
		
		//Delete session block
		$this->session->unset_userdata('week_photo_'.$next_week->week);
		
		redirect('/');
	}
	
	/**
	 * Preview prossima settimana
	 */
	function next_week_preview() {
		$this->_check_login();
		$this->load->helper('xhtml');
	
		//Get next week infos
		$next_week = $this->model_weeks->get_next_week();
		
		if ($next_week) {
			$this->view['week'] = $next_week;
			
			//Get next week photos
			$this->view['week_photos'] = $this->model_photos->get_week_visible_photos($next_week->week);
			
			//if (ENVIRONMENT == 'development') $this->output->enable_profiler(true);

			$this->renderLayout('week');
		}else show_error('Next week not set.');
	}
	
	/**
	 * Invio e-mail
	 */
	function send_email() {
		$this->_check_login();
		$this->load->helper(array('form', 'email'));
		$this->load->model('mailer');
		
		$this->load->library('email');
		
		//Avalaible dems
		$this->view['dems'] = array(
			0							=> '------',
			'invite' 					=> 'Invito generico',
			'thank-you-invite'			=> 'Grazie + invito (per amici)',
			'open-weeks'				=> 'Open weeks',
			'theme-of-the-week'			=> 'Theme of the week',
			'news'						=> 'Novit&agrave;'
		);

		if ($this->input->post('act')) {
		
			$language = $this->input->post('language');
			
			$emails = array();
			$done = '####<br />'.
			($language == 'italian' ? 'EMAIL INVIATE A' : 'EMAIL SENT TO') .
			':<br />';
			
			$names = $this->input->post('name');
			
		
			//Retreive all emails
			foreach ($this->input->post('email') as $key => $email) {
				$email = trim($email);
				if ($email != '' && valid_email($email)) {
					//Retreive the name associated to this email
					$name = trim($names[$key]);
					
					//Settings
					$this->email->from($this->config->item('website_email'), 'We Love Difference');
					$this->email->to($email);
					
					switch ($this->input->post('dem')) {
						case 'invite':
							$this->email->subject($language == 'italian' ? 'Cos\'è We Love Difference?' : 'What is We Love Difference');
							$this->email->message( $this->mailer->set_web(false)->DEM_invito($language) );
							break;	
						case 'thank-you-invite':
							$this->email->subject($language == 'italian' ? 'Siamo online!' : 'We\'re just online!');
							$this->email->message( $this->mailer->set_web(false)->DEM_thank_you_invite($name, $language) );
							break;	
						case 'open-weeks':
							$this->email->subject($language == 'italian' ? 'Arrivano le Open Weeks' : 'Open weeks');
							$this->email->message( $this->mailer->set_web(false)->DEM_open_weeks($name, $language) );
							break;
						case 'theme-of-the-week':
							$this->email->subject($language == 'italian' ? 'Il tema della settimana' : 'Theme of the week');
							$this->email->message( $this->mailer->set_web(false)->photo_themeweek($name, $language) );
							break;
						case 'news':
							$this->email->subject($language == 'italian' ? 'Novita\' su We Love Difference' : 'News on We Love Difference');
							$this->email->message( $this->mailer->set_web(false)->DEM_news($name, $language) );
							break;					
						default:
							show_error('Il template che hai scelto non &egrave; disponibile.');				
					} //end-case dem
					
					//Sends the email
					if ($this->email->send()) {
						$done .= $name.' ['.$email.'] # ';
					}
					
					//Clears email settings
					$this->email->clear();
					
				} //end-if mail valid
				
			} //end-foreach mail
			
			$this->session->set_flashdata('message', $done.'<br />####');
			redirect('anana/send_email');
			
		} //end-if act
		
		$this->renderLayout('admin/send_dem_email');
		
	}

	function send_likes_mail($id='') {
		$this->_check_login();
		if ($id != '') {
			$this->load->model('mailer');
			$this->load->library('email');
			
			
			$week = $this->model_weeks->get_week_by_real_id($id);
			if (!$week) return;

			$photos = $this->model_photos->get_week_photos($week->week, 'id, author_email, author_name, abstract, likes_count, week_id, user_language');
			
			$done = '';
			if (!$photos) die('No photos');
			
			foreach ($photos as $photo) {
				if (ENVIRONMENT == 'development') {
					$email = $this->config->item('administrator_email');
				}else{
					$email = $photo->author_email;
				}
				
				$this->email->to($email);
				
				$this->email->from($this->config->item('website_email'), 'We Love Difference');
				
				//Sets the params for the email
				$params = array(
					'id'		=> $photo->id,
					'name'		=> $photo->author_name,
					'title'		=> $photo->abstract ? $photo->abstract : lang('untitled'),
					'week'		=> $week->title,
					'week_week'	=> $week->week,
					'likes'		=> $photo->likes_count
				);
				
				//Sets the language
				$language = isset($photo->user_language) ? $photo->user_language : 'english';
				$this->email->subject($language == 'italian' ? 'I risultati della tua foto' : 'Your photo results');
				$this->email->message( $this->mailer->set_web(false)->likes_count($params, $language) );
				
				//Sends the email
				if ($this->email->send()) {
					$done .= $photo->author_name.' ['.$email.']<br />';
				}
				
				//Clears email settings
				$this->email->clear();
				
			}
			
			//Likes sent. update week
			$this->model_weeks->week_likes_sent($week->week);
			
			$this->session->set_flashdata('message',$done);
			redirect('anana/send_likes_mail');	
			
		}
		$this->renderLayout('admin/likes_mail_sent');
	}
	
	function send_published_mail($id='') {
		$this->_check_login();
	
		if ($id != '') {
			$this->load->model('mailer');
			$this->load->library('email');
			
			
			$week = $this->model_weeks->get_week_by_real_id($id);
			if (!$week) return;

			$photos = $this->model_photos->get_week_photos($week->week, 'id, author_email, author_name, user_language');
			
			$done = '';
			if (!$photos) show_error('Ehm... non ci sono foto! :|');
			
			foreach ($photos as $photo) {
				if (ENVIRONMENT == 'development') {
					$email = $this->config->item('administrator_email');
				}else{
					$email = $photo->author_email;
				}
				
				$this->email->to($email);
				
				$this->email->from($this->config->item('website_email'), 'We Love Difference');
				
				//Sets the params for the email
				$params = array(
					'id'		=> $photo->id,
					'name'		=> $photo->author_name,
					'week'		=> $week->week
				);
				
				//Sets the language
				$language = $photo->user_language ? $photo->user_language : 'english';
				$this->email->subject($language == 'italian' ? 'E\' lunedi\'!!' : 'It\'s Monday!!');
				$this->email->message( $this->mailer->set_web(false)->photo_published($params, $language) );
				
				//Sends the email
				if ($this->email->send()) {
					$done .= $photo->author_name.' ['.$email.']<br />';
				}
				
				//Clears email settings
				$this->email->clear();
				
			}
			
			//Likes sent. update week
			$this->model_weeks->week_published_sent($week->week);
			
			$this->session->set_flashdata('message',$done);
			redirect('anana/send_published_mail');	
			
			
			
		}
		$this->renderLayout('admin/published_mail_sent');
	}
	
	function send_themeweek_mail($id='') {
		$this->_check_login();
	
		if ($id != '') {
			$this->load->model('mailer');
			$this->load->library('email');
			
			
			$week = $this->model_weeks->get_week_by_real_id($id);
			if (!$week) return;

			$photos = $this->model_photos->get_week_photos($week->week, 'id, author_email, author_name, user_language');
			
			$done = '';
			if (!$photos) show_error('Ehm... non ci sono foto! :|');
			
			foreach ($photos as $photo) {
				if (ENVIRONMENT == 'development') {
					$email = $this->config->item('administrator_email');
				}else{
					$email = $photo->author_email;
				}
				
				$this->email->to($email);
				
				$this->email->from($this->config->item('website_email'), 'We Love Difference');
				
				//Sets the language
				$language = $photo->user_language ? $photo->user_language : 'english';
				$this->email->subject($language == 'italian' ? 'Il tema della settimana' : 'Theme of the week');
				$this->email->message( $this->mailer->set_web(false)->photo_themeweek($photo->author_name, $language));
				
				//Sends the email
				if ($this->email->send()) {
					$done .= $photo->author_name.' ['.$email.']<br />';
				}
				
				//Clears email settings
				$this->email->clear();
				
			}
			
			//Likes sent. update week
			$this->model_weeks->week_published_sent($week->week);
			
			$this->session->set_flashdata('message',$done);
			redirect('anana/send_themeweek_mail');	
			
			
			
		}
		$this->renderLayout('admin/published_mail_sent');
	}
	
	function send_winner_mail($id='') {
		$this->_check_login();
			
		if ($id != '') {
			$this->load->model('mailer');
			$this->load->library('email');
			
			
			$week = $this->model_weeks->get_week_by_real_id($id);
			if (!$week) return;

			$photo = $this->model_photos->get_winner_photo_for_week($week->week, 'id, author_email, author_name, user_language');
			
			$done = '';
			if (!$photo) show_error('Non c\'&egrave; alcun vincitore! :|');
			
		
			if (ENVIRONMENT == 'development') {
				$email = $this->config->item('administrator_email');
			}else{
				$email = $photo->author_email;
			}
			
			$this->email->to($email);
			
			$this->email->from($this->config->item('website_email'), 'We Love Difference');
			
			//Sets the params for the email
			$params = array(
				'id'		=> $photo->id,
				'name'		=> $photo->author_name,
				'week'		=> $week->week
			);
			
			//Sets the language
			$language = $photo->user_language ? $photo->user_language : 'english';
			$this->email->subject($language == 'italian' ? 'Sei il vincitore!!' : 'You\'re the winner!!');
			$this->email->message( $this->mailer->set_web(false)->photo_winner($params, $language) );
			
			//Sends the email
			if ($this->email->send()) {
				$done .= $photo->author_name.' ['.$email.']<br />';
			}
			
			//Clears email settings
			$this->email->clear();
			
			//Likes sent. update week
			$this->model_weeks->week_winner_sent($week->week);
			
			$this->session->set_flashdata('message',$done);
			redirect('anana/send_winner_mail');	
			
			
			
		}
		$this->renderLayout('admin/winner_mail_sent');
	}
	
	function open_week($id='') {
		if ($id != '') {
			$week = $this->model_weeks->get_week_by_real_id($id);
			$this->model_weeks->open_week($week->week);
			redirect('anana/list_weeks');
		}
	}
	
	function show_daily_log() {
		$this->_check_login();
		$name = APPPATH.'logs/log-'.date('Y-m-d').'.php';
		if (file_exists($name)) {
			$this->view['file'] = $name;
		}
		$this->renderLayout('admin/show_daily_log');
	}
	
	function make_backup() {
		$this->_check_login();
		
		// Load the DB utility class
		$this->load->dbutil();
		
		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup(); 
		
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$dir = APPPATH.'backup/';
		$filename = $dir.date('Y_m_d-H_i_s').'.gz';
		write_file($filename, $backup);
		
		
		
		$this->view['info'] = get_filenames($dir);
		$this->renderLayout('admin/backup_list');
	}
	
	function update_photo_priority() {
		$this->_check_login();
		
		$photos = array_filter(explode('|', $this->input->post('photos')));

		$max_priority = count($photos);
		
		if (!$photos) return;
		
		foreach ($photos as $photo_id) {
			$this->model_photos->change_priority_for_photo($photo_id, $max_priority);
			//echo 'Cambio priorita\' foto '.$photo_id.' a '.$max_priority.'\r\n';
			$max_priority--;
		}
		
	}

}
