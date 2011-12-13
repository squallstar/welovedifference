<?php 
$share_url = urlencode(site_url('welcome/week/'.$published_photo->week_id.'/photo/'.$published_photo->id));
$abstract = $published_photo->abstract ? $published_photo->abstract : lang('untitled');
$twt_share_msg = urlencode(lang('week').' '.$published_photo->week_id.' - '.$abstract.' '.site_url('welcome/week/'.$published_photo->week_id.'/photo/'.$published_photo->id));
$tumblr_text = urlencode('<strong>'.$abstract.'</strong><br /><br />Author: '.($published_photo->author_www?'<a href="'.$published_photo->author_www.'">'.$published_photo->author_name.'</a>':$published_photo->author_name).'<br />via <a href="'.base_url().'">www.welovedifference.com</a>');

if (LANG == 'italian') { ?>
<div id="the_white_thing" style="position:fixed" onclick="see.fade_intro();">
	<div class="the_center" style="top:50px;margin-top:0;position:absolute">
	
		<div class="modal">
			<h2>La tua foto &egrave; stata pubblicata!</h2>
			<div class="modal_content">
				
				
				<a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>"><img src="<?php echo base_url(); ?>widgets/icons/modal_facebook.png" alt="Share on Facebook" /></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="http://twitter.com/intent/tweet?via=welovediffs&hashtags=welovedifference&text=<?php echo $twt_share_msg; ?>"><img src="<?php echo base_url(); ?>widgets/icons/modal_twitter.png" alt="Share on Twitter" /></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a target="_blank" href="http://www.tumblr.com/share/photo?source=<?php echo urlencode(base_url() . $published_photo->source_file); ?>&clickthru=<?php echo $share_url; ?>&caption=<?php echo $tumblr_text; ?>"><img src="<?php echo base_url(); ?>widgets/icons/modal_tumblr.png" alt="Share on Tumblr" /></a>
				
				
				<br /><br />
				<span style="font-size:14px">Condividi la foto sui tuoi social network preferiti!</span>
				
			</div>
			<div class="modal_footer"></div>
		</div>
	
	
	</div>
</div>
<?php }else{ ?>
<div id="the_white_thing" style="position:fixed" onclick="see.fade_intro();">
	<div class="the_center" style="top:50px;margin-top:0;position:absolute">
	
		<div class="modal">
			<h2>Your photo has been published!</h2>
			<div class="modal_content">
				
				
				<a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>"><img src="<?php echo base_url(); ?>widgets/icons/modal_facebook.png" alt="Share on Facebook" /></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="http://twitter.com/intent/tweet?via=welovediffs&hashtags=welovedifference&text=<?php echo $twt_share_msg; ?>"><img src="<?php echo base_url(); ?>widgets/icons/modal_twitter.png" alt="Share on Twitter" /></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a target="_blank" href="http://www.tumblr.com/share/photo?source=<?php echo urlencode(base_url() . $published_photo->img_path); ?>&clickthru=<?php echo $share_url; ?>&caption=<?php echo $tumblr_text; ?>"><img src="<?php echo base_url(); ?>widgets/icons/modal_tumblr.png" alt="Share on Tumblr" /></a>
				
				
				<br /><br />
				<span style="font-size:14px">If you wish, share your wonderful photo on the main social networks!</span>
				
			</div>
			<div class="modal_footer"></div>
		</div>
	
	
	</div>
</div>

<?php } ?>
