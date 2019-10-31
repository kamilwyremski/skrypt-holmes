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
	
	if(isset($_POST['action'])){
		if($_POST['action']=='save_description' and isset($_POST['description'])){
			
			$user->saveDescription($_POST['description']);
			
		}elseif($_POST['action']=='change_password' and isset($_POST['old_password']) and $_POST['old_password']!='' and isset($_POST['new_password']) and $_POST['new_password']!='' and isset($_POST['repeat_new_password']) and $_POST['repeat_new_password']!=''){
			
			$user->changePassword($_POST);
			
		}
	}

	$user->getAllData();

	$settings['seo_title'] = lang('Settings').' - '.$settings['title'];
	$settings['seo_description'] = lang('Settings').' - '.$settings['description'];
}else{
	header("Location: ".$settings['base_url']."/".$links['login']."?redirect=".$links['settings']);
	die('redirect');
}
?>
