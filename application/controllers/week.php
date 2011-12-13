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

class Week extends SEE_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('encrypt');
	}

	function index() {
		show_error('Ehr... which week do you want? :(');
	}
	
	
	/**
	 * Action chiamata via AJAX per avere la settimana
	 */
	function get($num=null) {
		$this->_checkFireLinking();

		//if (CACHE) $this->output->cache(20);
		
		//Decode number
		$decoded_num = $this->encrypt->decode($this->input->post('key'));

		if ($num == $decoded_num) {
			
			$this->load->model(array('model_weeks', 'model_photos'));
			$this->load->helper('xhtml');
			
			//Get requested week
			$this->view['week'] = $this->model_weeks->get_week($num);
			
			if ($this->view['week']->visibility_date > date('Y-m-d')) {
				//Is a next week - not published yet
				return;
			}
			
			$this->view['title'] = $this->view['week']->title;
			
			//Get requested week photos
			$this->view['week_photos'] = $this->model_photos->get_week_visible_photos($num);
			
			if ($this->view['week']) $this->render('week');
			else show_error('Week not found. Maybe it has been removed?');
		}else show_error('Invalid key.', 400);
	}
	
	function delete_my_photo() {
		$this->load->model(array('model_auth', 'model_photos', 'model_weeks'));
		
		$next_week = $this->model_weeks->get_next_week();

		$posted_photo = $this->session->userdata('week_photo_'.$next_week->week);
			
		//Delete session block
		$this->session->unset_userdata('week_photo_'.$next_week->week);
		
		//Delete the db record + files
		if ($this->model_photos->delete_photo($posted_photo)) {
		
			//Delete database ip based blocks
			$this->model_auth->flush_week_blocks();
		
			$this->session->set_flashdata('message', lang('your_photo_deleted'));
			redirect('week/next');
		}else show_error('Cannot delete your photo. Please try later.');
		
	}
	
	
	/**
	 * Form di invio foto per la prossima settimana
	 * Nota: internamente viene usato WEEK_ID. Il numero vero della settimana viene usato
	 * per il nome della cartella con l'immagine su disco e il record nella tabella delle foto
	 * @param $mode null|ajax Tipo di richiesta
	 */
	function next_week($mode=false) {
		$this->load->model(array('model_auth', 'model_photos', 'model_weeks'));
		$this->load->helper('form');
		
		$this->view['next_week'] = $this->model_weeks->get_next_week();
		$next_week = $this->view['next_week'];
		
		$this->view['title'] = 'Next week &bull; '.$next_week->title;
		
		//Default view
		$view = 'form';
		
		if (!$mode) {
			//Get all the published weeks
			$this->view['weeks'] = $this->model_weeks->get_weeks();
			$this->view['next_week_view'] = true;
		}
		
		if ($this->session->userdata('week_photo_'.$this->view['next_week']->week)) {
			
			//Get posted photo info
			$inf = $this->session->userdata('week_photo_'.$this->view['next_week']->week);
			$info = $this->model_photos->get_photo($inf, 'img_path, author_name, abstract, img_width, img_height');
			if ($info) {
				$this->view['photo'] = array(
					'id'			=> $inf,
					'src'			=> $info->img_path,
					'author'		=> $info->author_name,
					'abstract'		=> $info->abstract,
					'img_width'		=> $info->img_width,
					'img_height'	=> $info->img_height
				);
				
			}else{
				if ($mode == 'ajax') {
					//Maybe the photo has been deleted. Redirect to the form
					$this->session->unset_userdata('week_photo_'.$this->view['next_week']->week);
					$this->render('next_week/form');
					return;
					
				}else redirect('/');
			}
			
			//Photo already posted
			$view = 'returning';
			
		}else{
			//Photo not posted yet
			
				//Form posted
				if ($this->input->post('act')) {
				
				$folders_permissions = 0777;
				
				//Increase limit
				ini_set('memory_limit', '128M');
				
				$upload_config = array(
					'upload_path'		=> FCPATH.'img/users/'.date('Ym').'/',
					'allowed_types'		=> $this->config->item('upload_allowed_files'),
					'max_size'			=> $this->config->item('upload_max_size'),
					'encrypt_name'		=> TRUE
				);
				
				//Check if tmp folder exists
				if (!file_exists($upload_config['upload_path'])) {
					mkdir($upload_config['upload_path'], $folders_permissions);
				}
				
				$this->load->library('upload', $upload_config);
				
				if (!$this->upload->do_upload('the_picture') && $this->input->post('guest_name')) {
					//$error = array('error' => $this->upload->display_errors());
					show_error('The file you sent was too big (max 4mb). <a href="'.base_url().'#next-week">Try again</a> with a smaller file.');
					
				}else{
					$img = $this->upload->data();
					
					//Resize paths
					$new_rel_path = 'img/weeks/'.$next_week->week.'/';
					$new_abs_path = FCPATH.$new_rel_path;
						
					//Check if folder exists - create it 
					if (!file_exists($new_abs_path)) mkdir($new_abs_path, $folders_permissions);
				
					//New file name (random things)
					$new_file_name = date('smHdYi').strtolower($img['file_name']);
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
					$website_url = str_replace(array('http://', 'https://'), '', $this->input->post('guest_www'));
					
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
						'author_name'	=> $this->input->post('guest_name'),
						'author_www'	=> $website_url,
						'author_email'	=> $this->input->post('guest_email'),
						'abstract'		=> $this->input->post('abstract'),
						'img_path'		=> $new_rel_path.$new_file_name,
						'source_file'	=> str_replace(FCPATH, '', $img['full_path']),
						'img_width'		=> $img_size[0],
						'img_height'	=> $img_size[1]
					);
					
					//Save the photo record
					if ($insert_id = $this->model_photos->add_photo($data)) {
						
						//Sets the cookie session
						$this->session->set_userdata('week_photo_'.$next_week->week, $insert_id);
						
						//Sets the database session
						$this->model_auth->block_week($next_week->id, $insert_id);
						
						$view = 'success';
						$this->view['guest_name'] = $this->input->post('guest_name');
						
						$this->view['photo'] = array(
							'id'	=> $insert_id,
							'src'	=> $data['img_path']
						);
						
						//Sets the publish cookie
						$this->session->set_userdata('publish_photo', $insert_id);
						
						//Sends the notification email only while in production
						if (ENVIRONMENT != 'development') {
							
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
					
					} //end-if save record into db success
					
				} //end-if file check
			} //end-if post act
			
			
		} //end-if check recurrent
		
		$show_modes = array(
			'form', 'ajax-form'
		);
		$show_views = array(
			'success' //'returning'
		);
		
		if ($next_week->is_open == 1 && !in_array($mode, $show_modes) && !in_array($view, $show_views)) {
			//Open week
			$this->load->helper('xhtml');
			
			//Get requested week photos
			$this->view['week_photos'] = $this->model_photos->get_week_visible_photos($next_week->week);
			$this->view['week'] = $next_week;
			
			if ($mode == 'ajax') {
				$this->render('week');
			}else{
				$this->renderLayout('week');
			}
			
		}else{
			//Closed week - shows the form
			if ($mode == 'ajax' || $mode == 'ajax-form') {
				$this->render('next_week/'.$view);
			}else{
				$this->renderLayout('next_week/'.$view);
			}
		}
		
	}
	
	/**
	 * Funzione per mettere i like alle foto
	 */
	function i_like_photo() {
		if (!$this->input->post('photo')) return;
		$this->_checkFireLinking();
		
		//Add a like to a photo
		$this->load->model('model_photos');
		$done = $this->model_photos->add_like_to_photo($this->input->post('photo'));
		if ($done) echo '1';		
	}

}