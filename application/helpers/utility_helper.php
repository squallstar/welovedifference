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
 
function date_helper($date=null) {
	if ($date) {
		if (strpos($date, '/')) {
			//Date is italian
			if (LANG == 'italian') return $date;
			else return implode('-', array_reverse(explode('/', $date)));
		}else if (strpos($date, '-')) {
			//Date is english
			if (LANG == 'english') return $date;
			else return implode('/', array_reverse(explode('-', $date)));
		}
	}
}
?>