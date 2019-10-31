<?php
/************************************************************************
 * The script of real estate HOLMES
 * Copyright (c) 2017 - 2019 Kamil Wyremski
 * http://wyremski.pl
 * kamil.wyremski@gmail.com
 * 
 * All right reserved
 * 
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES
 * BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN. DETECTION
 * COPY SCRIPT WILL RESULT IN A HIGH FINANSIAL PENALTY AND WITHDRAWAL
 * LICENSE THE SCRIPT
 * *********************************************************************/
 
require_once('../config/config.php');
include('offer.class.php');
	
if(!$settings['rss']){
	die(lang('RSS feed was switched off'));
}

function xmlEscape($string) {
	return $string;
	return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}

header("Content-Type: application/xml; charset=utf-8");

$offers = new offer;
$offers->loadOffers(20);

$rssfeed = '<?xml version="1.0" encoding="utf-8"?>';
$rssfeed .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
$rssfeed .= '<channel>';
$rssfeed .= '<title>'.$settings['title'].'</title>';
$rssfeed .= '<link>'.$settings['base_url'].'</link>';
if($settings['logo']!=''){
	$rssfeed .= ' <image>
		<title>'.$settings['title'].'</title>
		<url>'.$settings['logo'].'</url>
		<link>'.$settings['base_url'].'</link>
	</image>';
}
$rssfeed .= '<description>'.$settings['description'].'</description>';
$rssfeed .= '<language>'.$settings['lang'].'</language>';
$rssfeed .= '<lastBuildDate>'.date("D, d M Y H:i:s O").'</lastBuildDate>';
$rssfeed .= '<atom:link href="'.$settings['base_url'].'/php/rss.php" rel="self" type="application/rss+xml" />';

foreach($offers->offer_data as $key=>$value){
	$rssfeed .= '<item>';
	$rssfeed .= '<title>'.$value['name'].'</title>';
	$rssfeed .= '<link>'.$settings['base_url'].'/'.$value['id'].','.$value['name_url'].'</link>';
	$rssfeed .= '<guid>'.$settings['base_url'].'/'.$value['id'].','.$value['name_url'].'</guid>';
	$rssfeed .= '<pubDate>'.date("D, d M Y H:i:s O", strtotime($value['date'])).'</pubDate>';
	$rssfeed .= '<description>';
	$rssfeed .= substr(strip_tags(htmlspecialchars(strip_tags($value['description']), ENT_XML1, 'UTF-8')),0,400).'...';
	if($value['thumb']){
		$rssfeed .= '&lt;br&gt;&lt;br&gt;&lt;a href="'.$settings['base_url'].'/'.$value['id'].','.$value['name_url'].'"&gt;&lt;img src="'.$settings['base_url'].'/upload/photos/'.$value['thumb'].'" height="80"/&gt;&lt;/&gt;';
	}
	$rssfeed .= '</description>';
    $rssfeed .= '</item>';
}
$rssfeed .= '</channel>';
$rssfeed .= '</rss>';

echo $rssfeed;
?>