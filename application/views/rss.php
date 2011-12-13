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
 
 echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/">

	<channel>
		<title><?php echo $feed_name; ?></title>
		<atom:link href="<?php echo $feed_url; ?>" rel="self" type="application/rss+xml" />
		<link><?php echo base_url(); ?></link>
		<description><?php echo $page_description; ?></description>
		<lastBuildDate><?php echo rss_date(date('Y-m-d')); ?></lastBuildDate>
	
		<language><?php echo $page_language; ?></language>
		<sy:updatePeriod>daily</sy:updatePeriod>
		<sy:updateFrequency>1</sy:updateFrequency>
		<generator>http://www.squallstar.it</generator>
		
		<?php foreach ($posts as $week) {
			$descr = xml_convert(strip_tags(str_replace('<br />', " \n", $week->description)));
		
		?>
		<item>
		<title><?php echo $week->title; ?></title>
		<link><?php echo base_url(); ?>#week-<?php echo $week->week; ?></link>
		<pubDate><?php echo rss_date($week->visibility_date); ?></pubDate>
		<dc:creator>We Love Difference</dc:creator>
				<category><![CDATA[Photo]]></category>
		<category><![CDATA[Week]]></category>

		<guid isPermaLink="true"><?php echo base_url(); ?>#week-<?php echo $week->week; ?></guid>
		<description><![CDATA[<?php echo $descr; ?>]]></description>
			<content:encoded><![CDATA[<?php echo $descr; ?>]]></content:encoded>
		</item>
		<?php } ?>
	</channel>
</rss>
