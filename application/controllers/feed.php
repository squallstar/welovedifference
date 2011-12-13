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
 
Class Feed extends SEE_Controller {
	function index() {
	
	
		$this->load->helper(array('xml', 'text', 'rssdate'));
		$this->load->model(array('model_weeks'));
		
		$data['feed_name'] = 'We Love Difference';  
        $data['encoding'] = 'UTF-8';  
        $data['feed_url'] = base_url().'rss';  
        $data['page_description'] = lang('meta_description');  
        							
        $data['page_language'] = LANG == 'italian' ? 'it' : 'en';  
        $data['creator_email'] = $this->config->item('website_email');
          
        $data['posts'] = $this->model_weeks->get_weeks();
        
       
  		header("Content-Type: application/rss+xml");  
        $this->load->view('rss', $data);  
		
		
		
	}
}