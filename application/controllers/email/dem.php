<?php
Class Dem extends SEE_Controller {

	/*
	PROCEDIMENTO PER AGGIUNGERE UNA NUOVA MAIL:
	 1) creare un nuovo metodo nel model mailer.php
	 2) creare un nuovo ramo if nel metodo view del controller Dem
	 3) Nella classe Anana, metodo send_mail aggiungere all'array $dems la nuova dem
	 4) Nello stesso metodo aggiungere un case nello switch di invio
	 
	*/

	function view($dem_id=null, $param_1=null) {
		$this->load->model('mailer');
		
		//Removes the "%20" and "+"
		$param_1 = urldecode($param_1);
		
		if ($dem_id == 'invite') {
			echo $this->mailer->set_web(true)->DEM_invito(LANG);
		}else if ($dem_id == 'goodbye') {
			echo $this->mailer->set_web(true)->DEM_goodbye(LANG);
		}else if ($dem_id == 'thank-you-invite') {
			echo $this->mailer->set_web(true)->DEM_thank_you_invite($param_1, LANG);
		}else if ($dem_id == 'open-weeks') {
			echo $this->mailer->set_web(true)->DEM_open_weeks($param_1, LANG);
		}else if ($dem_id == 'photo-approved') {
			echo $this->mailer->set_web(true)->photo_approved($param_1, LANG);
		}else if ($dem_id == 'photo-rejected') {
			echo $this->mailer->set_web(true)->photo_rejected($param_1, LANG);
		}else if ($dem_id == 'theme-week') {
			echo $this->mailer->set_web(true)->photo_themeweek($param_1, LANG);
		}else if ($dem_id == 'news') {
			echo $this->mailer->set_web(true)->DEM_news($param_1, LANG);
		}else if ($dem_id == 'likes-count') {
			if (!$param_1) return;
			
			//Gets the key
			list($id, $key) = explode('-', $param_1);

			//Checks the key
			if ($id && $key && $key == $this->mailer->get_key($id)) {
				$this->load->model(array('model_photos', 'model_weeks'));
				
				//Get info about this photo
				$photo = $this->model_photos->get_photo($id, 'author_name, abstract, likes_count, week_id, user_language');
				
				//And about the week
				$week = $this->model_weeks->get_week($photo->week_id);
				
				//Sets the params for the email
				$params = array(
					'id'		=> $id,
					'name'		=> $photo->author_name,
					'title'		=> $photo->abstract ? $photo->abstract : lang('untitled'),
					'week'		=> $week->title,
					'week_week'	=> $week->week,
					'likes'		=> $photo->likes_count
				);
				
				//Sets the language
				$language = isset($photo->user_language) ? $photo->user_language : LANG;
				
				echo $this->mailer->set_web(true)->likes_count($params, $language);
				
			}// end-if check key
			
		}else if ($dem_id == 'photo-published') {
			if (!$param_1) return;
						
			//Gets the key
			list($id, $key) = explode('-', $param_1);

			//Checks the key
			if ($id && $key && $key == $this->mailer->get_key($id)) {
				$this->load->model(array('model_photos', 'model_weeks'));
				
				//Get info about this photo
				$photo = $this->model_photos->get_photo($id, 'author_name, week_id, user_language');
				
				//Sets the params for the email
				$params = array(
					'id'	=> $id,
					'name'	=> $photo->author_name,
					'week'	=> $photo->week_id
				);
				
				//Sets the language
				$language = isset($photo->user_language) ? $photo->user_language : LANG;
				
				echo $this->mailer->set_web(true)->photo_published($params, $language);
				
			} //end-if check key
			
		}else if ($dem_id == 'photo-winner') {
					if (!$param_1) return;
								
					//Gets the key
					list($id, $key) = explode('-', $param_1);
		
					//Checks the key
					if ($id && $key && $key == $this->mailer->get_key($id)) {
						$this->load->model(array('model_photos', 'model_weeks'));
						
						//Get info about this photo
						$photo = $this->model_photos->get_photo($id, 'author_name, week_id, user_language');
						
						//Sets the params for the email
						$params = array(
							'id'	=> $id,
							'name'	=> $photo->author_name,
							'week'	=> $photo->week_id
						);
						
						//Sets the language
						$language = isset($photo->user_language) ? $photo->user_language : LANG;
						
						echo $this->mailer->set_web(true)->photo_winner($params, $language);
						
					} //end-if check key
					
				} //end-if photo published
		
		return;
	}
	
	/* Action per inviare a tutti la mail news  */
	function news() {
	die; 
		error_reporting(E_ALL);
		$this->load->model(array('model_auth', 'mailer', 'model_photos'));
		$this->load->helper('email');
		$this->load->library('email');
		
		if (!$this->model_auth->is_logged_in()) {
			redirect('/anana/login');
		}

		$users = $this->model_photos->get_all_users();
		if (!$users) show_error('No users found');
			
		$done = '';
				  
		foreach ($users as $user) {
			if (ENVIRONMENT != 'development') {
			
				$this->email->from($this->config->item('website_email'), 'We Love Difference');
				$this->email->to($user->author_email);
				$this->email->subject($user->user_language == 'italian' ? 'Novita\' su We Love Difference' : 'News on We Love Difference');
				
				$msg = $this->mailer->set_web(false)->DEM_news($user->author_name, $user->user_language);
				
				//Sets the email message
				$this->email->message($msg);
				
				$sent = false;
				//$sent = $this->email->send();
				
				if ($sent) {
					$done .= $user->author_name.' ['.$user->author_email.']<br />';
				}else{
					$done .= $user->author_name.' [NON INVIATA: <strong>'.$user->author_email.'</strong>]<br />';
				}
				
				//Clears email settings
				$this->email->clear();
			}
			
		}
		echo $done;
		return;
	}
	
	/* Action per inviare a tutti la mail news  */
	function goodbye() {
		die;
		error_reporting(E_ALL);
		$this->load->model(array('model_auth', 'mailer', 'model_photos'));
		$this->load->helper('email');
		$this->load->library('email');
		
		if (!$this->model_auth->is_logged_in()) {
			redirect('/anana/login');
		}

		$users = $this->model_photos->get_all_users();
		if (!$users) show_error('No users found');
		
		$we_are = array();
		foreach ($users as $user) {
			$we_are[$user->author_email] = $user;
		}
			
		$done = '';
				  
		foreach (array_keys($we_are) as $email) {
			if (ENVIRONMENT != 'development') {
			
				$user = $we_are[$email];
			
				$this->email->from($this->config->item('website_email'), 'We Love Difference');
				$this->email->to($email);
				$this->email->subject($user->user_language == 'italian' ? '...Fine!' : '...The end!');
				
				$msg = $this->mailer->set_web(false)->DEM_goodbye($user->user_language);
				
				//Sets the email message
				$this->email->message($msg);
				
				$sent = false;
				//$sent = $this->email->send();
				
				if ($sent) {
					$done .= $user->author_name.' ['.$user->author_email.'] Lingua: '.$user->user_language.'<br />';
				}else{
					$done .= $user->author_name.' [NON INVIATA: <strong>'.$user->author_email.'</strong>] Lingua: '.$user->user_language.'<br />';
				}
				
				//Clears email settings
				$this->email->clear();
			}
			
		}
		echo $done;
		return;
	}
}