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
 
/**
 * Helper per stampare l'xhtml di una foto
 */
function print_photo($obj) {
	$is_liked = (isset($obj->is_liked) && $obj->is_liked == true) ? ' active' : '';
	$share_url = urlencode(site_url('welcome/week/'.$obj->week_id.'/photo/'.$obj->id));
	$twt_share_msg = urlencode(lang('week').' '.$obj->week_id.' - '.$obj->abstract.' '.site_url('welcome/week/'.$obj->week_id.'/photo/'.$obj->id));
	return
	'<div class="photo" data-photoid="'.$obj->id.'">'.
		'<a href="'.base_url().$obj->source_file.'" class="ltbx" title="'.str_replace('"', '', $obj->abstract).'"><img src="'.base_url().$obj->img_path.'" width="'.$obj->img_width.'" height="'.$obj->img_height.'" border="0" /></a>'.
		'<div class="cont_left">'.
			'<div class="abstract">'.($obj->abstract ? $obj->abstract : lang('untitled')).'</div>'.
			'<div class="by">'.lang('posted_by').' <strong>'.$obj->author_name.'</strong></div>'.
			($obj->author_www ? '<a target="_blank" rel="external nofollow" href="http://'.$obj->author_www.'">'.$obj->author_www.'</a><br />':'').
			'<div class="social_share">'.
				'<a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u='.$share_url.'" title="'.lang('share_on').' Facebook"><img src="'.base_url().'widgets/icons/fb_photo.png" border="0" alt="Share on Facebook" /></a>'.
				'<a href="http://twitter.com/intent/tweet?via=welovediffs&hashtags=welovedifference&text='.$twt_share_msg.'" title="'.lang('share_on').' Twitter"><img src="'.base_url().'widgets/icons/twt_photo.png" border="0" alt="Share on Twitter" /></a>'.
				'<a target="_blank" href="http://www.tumblr.com/share/photo?source='.urlencode(base_url().$obj->source_file).'&clickthru='.$share_url.'&caption='.urlencode('<strong>'.($obj->abstract ? $obj->abstract : lang('untitled')).'</strong><br /><br />Author: '.($obj->author_www?'<a href="'.$obj->author_www.'">'.$obj->author_name.'</a>':$obj->author_name).'<br />via <a href="'.base_url().'">www.welovedifference.com</a>').'" title="'.lang('share_on').' Tumblr"><img src="'.base_url().'widgets/icons/tum_photo.png" border="0" alt="Share on Tumblr" /></a>'.
			'</div>'.
		'</div>'.
		'<div class="cont_right">'.
			'<a href="#" class="like'.$is_liked.'" photoid="'.$obj->id.'" onclick="return see.like_photo(this);">'.$obj->likes_count.'</a>'.
		'</div>'.
		'<div class="clear"></div>'.
	'</div>'."\n";
}