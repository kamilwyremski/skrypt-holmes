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

if(isset($_POST['action']) and $_POST['action']=='register' and isset($_POST['email']) and isset($_POST['username']) and isset($_POST['password']) and isset($_POST['password_repeat']) and isset($_POST['captcha'])){

	$user -> register($_POST);

}

if(!isset($_GET['facebook_login']) and $settings['facebook_login']==1 and $settings['facebook_api']!='' and $settings['facebook_secret']!=''){
	require_once('php/facebook.php');
}

$settings['seo_title'] = lang('Registration').' - '.$settings['title'];
$settings['seo_description'] = lang('Registration on the website').' - '.$settings['description'];

?>

