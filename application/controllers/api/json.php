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
API LIST

	api/json/weeks/list				La lista delle settimane pubblicate
	api/json/week/next				La prossima settimana
	api/json/week/(:num)/detail		Le foto della settimana richiesta e descrizione settimana

*/

Class Json extends SEE_Controller {

	private $msg = 200;
	
	public function __construct() {
		$this->force_language = 'en';
		parent::__construct();
	}

	function _set_json_header() {
		$this->output->set_header('Content-Type: text/plain; charset=utf-8');
	}
	
	function _set_message($msg) {
		$this->msg = $msg;
	}
	
	function _show($data, $msg=null) {
		$content = json_encode(array(
			'status_code'	=> $msg ? $msg : $this->msg,
			'base_url'		=> base_url(),
			'data'			=> $data
			)
		);
		
		$this->load->view('api/display', array('content' => $content));
	}
	
	function index() {
		show_error('Api request not set.');
	}
	

	function weeks($switch=null, $param=null) {
		$this->load->model(array('model_weeks', 'model_photos'));
		$this->_set_json_header();
		
		if (CACHE) $this->output->cache(60);
		
		if ($switch == 'list') {
			$list = array();
			
			//Get next week
			$next_week = $this->model_weeks->get_next_week();
			if ($next_week->is_open) {
				//If open, add it
				$list[] = array(
					'week'			=> $next_week->week,
					'title'			=> $next_week->title,
					'description'	=> str_replace('<br />', ' ', $next_week->description),
					'deadline'		=> $next_week->visibility_date,
					'status'		=> $next_week->visibility_date > date('Y-m-d') ? 'open' : 'closed',
					'api'			=> array(
						'photos'	=> 'api/json/week/'.$next_week->week.''
					)
				);
			}
			
			//List the published weeks
			$weeks = $this->model_weeks->get_weeks();
			
			
			foreach ($weeks as $week) {
				$list[] = array(
					'week'			=> $week->week,
					'title'			=> $week->title,
					'description'	=> str_replace('<br />', ' ', $week->description),
					'deadline'		=> $week->visibility_date,
					'status'		=> $week->visibility_date > date('Y-m-d') ? 'open' : 'closed',
					'api'			=> array(
						'photos'	=> 'api/json/week/'.$week->week.''
					)
				);
			}
			return $this->_show($list);		
		} //endif list
		
		
	}
	
	function week($num=null) {
		$this->load->model(array('model_weeks', 'model_photos'));
		$this->_set_json_header();
		
		if (CACHE) $this->output->cache(60);

		if ($num != 'next') {
		
			// Action: api/week/(:int)
			$week = $this->model_weeks->get_week($num);
			
			if (!$week) {
				$this->_set_message(404);
				return $this->_show('Requested week is not available.');
				
			}else if ($week->visibility_date > date('Y-m-d') && !$week->is_open){
			
				//Is a next week (not published yet)
				$this->_set_message(204);
				return $this->_show('Requested week is not published yet.');
			
			}else{
				$photos = $this->model_photos->get_week_visible_photos($num);
				
				$list = array(
					'week'			=> $num,
					'title'			=> $week->title,
					'description'	=> $week->description,
				'deadline'		=> $week->visibility_date,
				'status'		=> $week->visibility_date > date('Y-m-d') ? 'open' : 'closed',
					'photos'		=> array()
				);
			
			
				if (count($photos)) {
					foreach ($photos as $photo) {
						$list['photos'][] = array(
							'uid'		=> 'WLD0'.$photo->id,
							'title'		=> isset($photo->abstract) ? $photo->abstract : 'null',
							'thumb'		=> $photo->img_path,
							'src'		=> $photo->source_file,
							'width'		=> $photo->img_width,
							'height'	=> $photo->img_height,
							'author_name'	=> $photo->author_name,
						'author_www'	=> $photo->author_www,
						'likes'			=> $photo->likes_count
						);
					}
				}
				
				return $this->_show($list);			
			}			
		
		} //endif is int ($num)
		
		else if ($num == 'next') {
			//Next week, action: api/week/next
			$next_week = $this->model_weeks->get_next_week();
			$list = array(
				'week'			=> $next_week->week,
				'title'			=> $next_week->title,
				'description'	=> $next_week->description,
				'deadline'		=> $next_week->deadline,
				'status'		=> $next_week->visibility_date > date('Y-m-d') ? 'open' : 'closed',
				'api'			=> array(
					'photos'	=> 'api/week/'.$next_week->week
				)
			);
			
			return $this->_show($list);
		}

	}
	
	//Simile al metodo nextweek sul controller week
	function send($action) {
		if ($action == 'photo.nextweek') {
			//Azione chiamata per inviare fotografie
			
			//Carico le librerie
			$this->load->model(array('model_auth', 'model_photos', 'model_weeks'));
			
			//Get di questa settimana
			$next_week = $this->model_weeks->get_next_week();
			
			$uid = $this->input->post('uid');
			
			//Controllo se è stato postato l'uid
			if (!$uid) {
				return $this->_show('Unique Identifier not set.', 400);
			}else{
				//Controllo se questo UID ha già inviato foto
				$can_send = $this->model_photos->uid_can_send_photo($uid, $next_week->week);
				if (!$can_send) {
					return $this->_show('That UID already sent its photo for this week.', 400);
				}
			}
			
			
			
			//Permessi sulle cartelle da creare
			$folders_permissions = 0777;
			
			//Increase limit
			ini_set('memory_limit', '128M');
			
			$upload_config = array(
				'upload_path'		=> FCPATH.'img/users/EXT_'.date('Ym').'/',
				'allowed_types'		=> $this->config->item('upload_allowed_files'),
				'max_size'			=> $this->config->item('upload_max_size'),
				'encrypt_name'		=> TRUE
			);
			
			//Check if tmp folder exists
			if (!file_exists($upload_config['upload_path'])) {
				mkdir($upload_config['upload_path'], $folders_permissions);
			}
			
			$this->load->library('upload', $upload_config);
			
			if (!$this->input->post('user_name')) {
				return $this->_show('You must fill your name (field: user_name).', 406);	
			}
			
			
			if (!$this->upload->do_upload('user_photo')) {			
				return $this->_show('The file you sent was too big (max 4mb) or invalid. Try again with another file.', 406);	
			}else{
				//Upload ok!
				$img = $this->upload->data();

				//Resize paths
				$new_rel_path = 'img/weeks/'.$next_week->week.'/';
				$new_abs_path = FCPATH.$new_rel_path;
					
				//Check if folder exists - create it 
				if (!file_exists($new_abs_path)) mkdir($new_abs_path, $folders_permissions);
			
				//New file name (random things) - EXT indica esterno
				$new_file_name = 'EXT_'.date('smHdYi').strtolower($img['file_name']);
				$new_full_path = $new_abs_path.$new_file_name;
			
				//Image resize parameters
				$image_config = array(
					'source_image'		=> $img['full_path'],
					'new_image'			=> $new_full_path,
					'quality'			=> $this->config->item('photo_thumb_jpeg_quality'),
					'create_thumb'		=> FALSE,
					'maintain_ratio'	=> TRUE,
					'width'				=> $this->config->item('photo_thumb_width'),
					'height'			=> $this->config->item('photo_thumb_height'),
					'master_dim'		=> 'width'
				);
				
				//Loads the image library and resize the image
				$this->load->library('image_lib', $image_config);
				$this->image_lib->resize();

				//Gets the new image size
				$img_size = getimagesize($new_full_path);
				
				//Gets a safe link
				$website_url = str_replace(array('http://', 'https://'), '', $this->input->post('user_www'));
				
				//Resize also the original image for the lightbox
				$this->image_lib->clear();
				$image_config = array(
					'source_image'		=> $img['full_path'],
					'new_image'			=> $img['full_path'],
					'quality'			=> $this->config->item('photo_jpeg_quality'),
					'create_thumb'		=> FALSE,
					'maintain_ratio'	=> TRUE,
					'width'				=> $this->config->item('photo_resized_width'),
					'height'			=> $this->config->item('photo_resized_height'),
					'master_dim'		=> 'height'
				);
				$this->image_lib->initialize($image_config); 
				$this->image_lib->resize();
				
				//Prepare data to save
				$data = array(
					'week_id'		=> $next_week->week,
					'author_name'	=> $this->input->post('user_name'),
					'author_www'	=> $website_url,
					'author_email'	=> $this->input->post('user_email'),
					'abstract'		=> $this->input->post('photo_title'),
					'img_path'		=> $new_rel_path.$new_file_name,
					'source_file'	=> str_replace(FCPATH, '', $img['full_path']),
					'img_width'		=> $img_size[0],
					'img_height'	=> $img_size[1],
					'uid'			=> $uid
				);
				
				//Save the photo record
				if ($insert_id = $this->model_photos->add_photo($data)) {

					//Sends the notification email only while in production
					if (ENVIRONMENT != 'development' && FALSE) {
						
						//Loads the email class
						$this->load->library(array('parser', 'email'));
						
						//Send internal notification email
						$em_data = array(
							'week'		=> $next_week->title,
							'image'		=> base_url().$data['img_path'],
							'author'	=> $data['author_name'],
							'title'		=> $data['abstract'],
							'email'		=> $data['author_email'],
							'www'		=> $data['author_www'],
							'approve'	=> base_url().'anana/week/'.$next_week->week.'/approve/'.$insert_id,
							'admin_url'		=> base_url().'anana'
						);
						$body = $this->parser->parse('email/new_photo_received', $em_data, true);
						
						$this->email->from($this->config->item('website_email'), 'We Love Difference');
						$this->email->to($this->config->item('administrator_email')); 
						$this->email->cc($this->config->item('moderator_email')); 
						$this->email->message($body);
						$this->email->subject('Nuova foto ricevuta');
						$this->email->send();						
					
					} //end-if environment check
					
					return $this->_show(array('sent' => 'TRUE'));
				
				} //end-if save record into db success
				
				
			} //end-if upload ok
			
			
			
		}else{
			return $this->_show('Method not set', 400);
		}
	}


}