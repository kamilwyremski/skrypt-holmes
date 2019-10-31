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

$get_new_session_code = true;

if(isset($_POST['action']) and $_POST['action'] == 'login' and isset($_POST['session_code']) and $_POST['session_code']!='' and isset($_POST['username']) and $_POST['username']!='' and isset($_POST['password']) and $_POST['password']!=''){
	
	$user->login($_POST);
	
}elseif(isset($_GET['activation_code']) and $_GET['activation_code']!=''){
	
	if($user->checkCodeAndActivate($_GET['activation_code'])){
		$smarty->assign("info", lang('Account has been activated, you can now log in.'));
	}else{
		$smarty->assign("error", lang('Incorrect activation code or the account has already been activated.'));
	}
	
}elseif(isset($_GET['complete_data']) and $_GET['complete_data']!='' and $user->checkCompleteData($_GET['complete_data'])){
	
	if(isset($_POST['action']) and $_POST['action']=='complete_data'){
		
		$user->completeData($_GET['complete_data'], $_POST);
		
	}

	$smarty->assign("form_complete_data", true);
	$get_new_session_code = false;
}

if($get_new_session_code){
	if(!isset($_GET['facebook_login']) and $settings['facebook_login']==1 and $settings['facebook_api']!='' and $settings['facebook_secret']!=''){
		require_once('php/facebook.php');
	}
	$user->newSessionCode();
}

if(isset($_GET['info'])){show_info($_GET['info']);}

$settings['seo_title'] = lang('Log in').' - '.$settings['title'];
$settings['seo_description'] = lang('Log in on the website').' - '.$settings['description'];
?>