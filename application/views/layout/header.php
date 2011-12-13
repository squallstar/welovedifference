<?php

$tweet_url = 'http://twitter.com/intent/tweet?via=welovediffs&hashtags=welovedifference&text='.urlencode(lang('twitter_share_text'));

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo LANG=='italian'?'it':'en'; ?>" xml:lang="<?php echo LANG=='italian'?'IT':'EN'; ?>">
<head>
<title><?php echo isset($title) ? $title.' &bull; ' : ''; ?>We Love Difference</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="<?php echo isset($meta_description) ? $meta_description.'' : lang('meta_description'); ?>" />
<meta name="keywords" content="we love difference, we love, difference, points of view, theme, share, photo, different, squallstar, mintsugar" />
<link type="text/plain" rel="author" href="<?php echo base_url(); ?>humans.txt" />
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold,500">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css?v=3" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/lightbox.css" />
<link rel="shortcut icon" href="<?php echo base_url(); ?>widgets/favicon.gif" type="image/gif" />
<link rel="apple-touch-icon-precomposed" href="<?php echo base_url(); ?>apple-touch-icon.png" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="social_pages">
		<div class="asocial twitter">
			<h1><?php echo lang('twitter_h1'); ?></h1>
			<p><?php echo lang('twitter_h2'); ?></p>
			<div class="buttons">
				<a href="<?php echo $tweet_url; ?>" class="btn" onclick="see.fade_social().track('Socials', 'Twitter-Share', '');"><?php echo lang('share'); ?></a>
				<a href="<?php echo $this->config->item('twitter_profile'); ?>" target="_blank" title="Follow us on Twitter" class="btn last" onclick="see.fade_social().track('Socials', 'Twitter-Profile', '');"><?php echo lang('follow_us'); ?></a>
				<div class="clear"></div>
			</div>
		</div>
		<div class="asocial facebook">
			<h1><?php echo lang('facebook_h1'); ?></h1>
			<p><?php echo lang('facebook_h2'); ?></p>
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode($this->config->item('fb_page')); ?>" scrolling="no" frameborder="0" class="fbframe"></iframe>
			<div class="buttons">
				<a href="<?php echo $tweet_url; ?>" class="btn" onclick="see.fade_social().track('Socials', 'Facebook-Share', '');"><?php echo lang('share'); ?></a>
				<a href="<?php echo $this->config->item('fb_page'); ?>" target="_blank" title="Our Facebook Page" class="btn last" onclick="see.fade_social().track('Socials', 'Facebook-Page', '');"><?php echo lang('our_facebook_page'); ?></a>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<?php if (isset($published_photo)) {
		   $this->load->view('misc/photo_published');
		  }else if (isset($load_intro)) {
			   $this->load->view('misc/intro');
	      }else if (isset($load_winner)) {
	      	   $this->load->view('misc/winner');
	      }
	?><div class="header_container">
		<div class="header">
			<div class="social_icons">
				<a title="Facebook Fan page" onclick="see.show_social('facebook');" href="#"><img src="<?php echo base_url(); ?>widgets/icons/facebook.png" border="0" alt="" /></a>
				<a href="#" onclick="see.show_social('twitter');" title="We're on Twitter!"><img src="<?php echo base_url(); ?>widgets/icons/twitter1.png" border="0" alt="" /></a>
				<a title="View our RSS feed" href="<?php echo base_url(); ?>feed" target="_blank" rel="external"><img src="<?php echo base_url(); ?>widgets/icons/rss.png" border="0" alt="" /></a>
			</div>
			<div class="helpers">
				<a href="<?php echo base_url().'change-language/'.(LANG=='italian'?'en':'it'); ?>"><?php echo lang('change_language'); ?></a><div class="sep">|</div>
				<a href="#how-it-works" onclick="see.load_page('misc/howitworks');"><?php echo lang('how_it_works'); ?></a><div class="sep">|</div>
				<a href="#drop-a-line" onclick="see.load_page('misc/drop');"><?php echo lang('drop_a_line'); ?></a><div class="sep">|</div>
				<a href="#api-userguide" onclick="see.load_page('api/userguide/');"><?php echo lang('developers_api'); ?></a><div class="sep">|</div>
				<a href="#credits" onclick="see.load_page('misc/credits');"><?php echo lang('credits'); ?></a>
				<div class="clear"></div>
			</div>
			
			<a href="<?php echo base_url(); ?>" class="logo"></a>
			<div class="white_line"></div>
		</div>
		<div class="green_line"></div>
	</div>
	
	<div class="right_white"></div>
	
	<?php if (!isset($admin)) { ?>
	<div id="nav">
		<?php if (isset($next_week)) { ?>
		<div class="box next<?php echo $next_week->is_open && !isset($selected_week) ? ' active' : ''; ?>" week="next-week" key="null">
			<h2 class="dark"><?php echo lang( $next_week->is_open ? 'open_week' : 'next_week').' <sup>['.$next_week->week.']</sup>'; ?></h2>
			<h3 class="dark"><span class="title"><?php echo $next_week->title; ?></span><br />
			<span class="thegrey"><?php echo lang('best_before').' '.date_helper($next_week->deadline); ?></span></h3>
			<?php
			$photo_been_posted = $this->session->userdata('week_photo_'.$next_week->week);
			if (!$photo_been_posted) { ?>
			<p><?php echo lang('partecipate_text'); ?></p>
			<?php if (!$next_week->is_open) { ?>
			<a href="#next-week"><?php echo lang('send_your_photo'); ?></a>
			<?php }
			} ?>
			
		</div>
		<?php } ?>
		
		<?php
		$first = isset($next_week->is_open) && $next_week->is_open ? false : true;
		if (isset($selected_week)) $first = false;
		else $selected_week = false;
		
		foreach ($weeks as $week) {
			?>
			<div class="box<?php if ( ($first && !isset($load_intro)) || $selected_week == $week->week) { $first = false; echo ' active'; } ?>" week="<?php echo $week->week; ?>" key="<?php echo $this->encrypt->encode($week->week); ?>">
				<h2><?php echo lang('week').' '.$week->week; ?> (<?php echo date_helper($week->visibility_date); ?>)</h2>
				<h3><?php echo $week->title; ?></h3>
			</div>			
			<?php
		}
		?>
	
	</div>
	
	<?php }else if (!isset($disable_admin_menu)) { $this->load->view('admin/menu'); } ?>
	
	<div id="col">
		<div class="container">
