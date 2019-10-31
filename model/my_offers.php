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
 
if(!isset($settings['base_url'])){
	die('Access denied!');
}

if($user->logged_in){
	$offers = new offer;
	$offers->loadOffers($settings['limit_page'],'my_offers');

	if($settings['search_box_kind']){
		get_offers_kinds();
	}
	if($settings['search_box_state']){
		get_offers_state();
	}
	if($settings['search_box_type']){
		get_offers_type();
	}
	
	$settings['seo_title'] = lang('My offers').' - '.$settings['title'];
	$settings['seo_description'] = lang('My offers').' - '.$settings['description'];
	
}else{
	header("Location: ".$settings['base_url']."/".$links['login']."?redirect=".$links['my_offers']);
	die('redirect');
}



?>