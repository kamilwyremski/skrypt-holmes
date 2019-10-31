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

$page = 'add';
include('model/'.$page.'.php');
	
if(!empty($_GET['code'])){$code = $_GET['code'];}else{$code = '';}

if(isset($_GET['id']) and $_GET['id']>0 and $offer->checkPermissions($_GET['id'],$code)){
	$this_offer = $offer->loadOffer($_GET['id']);
	$smarty->assign("offer", $this_offer);
	
	$settings['seo_title'] = lang('Edit offer').' - '.$settings['title'];
	$settings['seo_description'] = lang('Edit offer').' - '.$settings['description'];
}else{
	header("Location: ".$settings['base_url']."/".$links['add']);
	die('redirect');
}



?>