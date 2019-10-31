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

if(isset($_POST['action']) and $_POST['action'] == 'reset_password' and isset($_POST['username']) and $_POST['username']!='' and isset($_POST['captcha']) and $_POST['captcha']!=''){
	
	$user->resetPassword($_POST);
	
}elseif(isset($_GET['new_password']) and $_GET['new_password']!=''){

	$user_id = $user->resetPasswordNew($_GET['new_password'])['user_id'];
	if($user_id){
		if(isset($_POST['action']) and $_POST['action'] == 'new_password' and isset($_POST['password']) and isset($_POST['password_repeat'])){
			$user->resetPasswordNewCheck($user_id,$_POST,$_GET['new_password']);
		}
		$smarty->assign("form_new_password", true);
	}else{
		$smarty->assign("error", lang('Incorrect or inactive password reset code.'));
	}
	
}

if(isset($_GET['info'])){show_info($_GET['info']);}

$settings['seo_title'] = lang('Reset password').' - '.$settings['title'];
$settings['seo_description'] = lang('Reset password').' - '.$settings['description'];

?>