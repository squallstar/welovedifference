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
 
function rss_date($date='') {
	if ($date=='') return;
	if (strpos($date, '/')) {
		//Normalize an italian date to english
		$date = implode('-', array_reverse(explode('/', $date)));
	}
	
	$dateValue = $date.' 12:30:00';
	$time=strtotime($dateValue); 
	return date('D, d M Y H:i:s',$time);
}