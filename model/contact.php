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

$sth = $db->prepare('select * from '._DB_PREFIX_.'info where page="contact" limit 1');
$sth->execute();
$contact_page = $sth->fetch(PDO::FETCH_ASSOC);
$sth->closeCursor();
if($contact_page!=''){
	$smarty->assign("contact_page", $contact_page);
}
	
if(isset($_POST['action']) and $_POST['action']=='send_message' and isset($_POST['name']) and isset($_POST['email']) and isset($_POST['message']) and isset($_POST['captcha'])){

	if($_POST['captcha']!=$_SESSION['captcha']){
		$error['captcha'] = lang('Invalid captcha code.');
	}else{
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$error['email'] = lang('Incorrect e-mail address.');
		}
		if($_POST['name']==''){
			$error['name'] = lang('Enter your name');
		}
		if($_POST['message']==''){
			$error['message'] = lang('Enter your message');
		}
		if(!isset($_POST['rules'])){
			$error['rules'] = lang('This field is mandatory.');
		}
	}
	
	if(isset($error)){
		$error['alert'] = lang('The message was not sent');
		$smarty->assign("error", $error);
		$smarty->assign("input", array('name'=>$_POST['name'], 'email'=>$_POST['email'], 'message'=>$_POST['message']));
	}else{
		
		if(send_mail('contact_form',$settings['email'],array('name'=>$_POST['name'], 'email'=>$_POST['email'], 'message'=>$_POST['message']))){
			$smarty->assign("info", lang('The message was correctly sent'));
		}else{
			$error['alert'] = lang('The message was not sent');
			$smarty->assign("error", $error);
		}
	}
}

$settings['seo_title'] = lang('Contact with us').' - '.$settings['title'];
$settings['seo_description'] = lang('Contact with us').' - '.$settings['description'];
	
?>