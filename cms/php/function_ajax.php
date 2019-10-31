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
 
session_start(); 

require_once('../../config/config.php');
include('user_cms.php');
include('../../php/user.class.php');

if(!_CMS_TEST_MODE_ and $user_cms->logged_in and isset($_POST['data'])){
	$post = $_POST['data'];
	if($post['action']=='activate_user' and isset($post['id']) and $post['id']>0){
		$user->activate($post['id']);
	}elseif($post['action']=='set_moderator' and isset($post['id']) and $post['id']>0){
		$user->setModerator($post['id']);
	}elseif($post['action']=='unset_moderator' and isset($post['id']) and $post['id']>0){
		$user->unSetModerator($post['id']);
	}elseif($post['action']=='position_offers_options' and isset($post['id']) and isset($post['position']) and isset($post['plusminus'])){
		setPosition('offers_options',$post['id'],$post['position'],$post['plusminus']);
	}
}else{
	die('Access denied!');
}
?>