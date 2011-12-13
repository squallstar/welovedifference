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
 
class Userguide extends SEE_Controller {
	function index() {
		if (CACHE) $this->output->cache(300); //5 hours
		$this->render('api/user_guide/welcome');
	}
}