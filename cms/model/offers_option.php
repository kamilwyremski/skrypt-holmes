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

if($user_cms->logged_in){

	include('../php/offers_options.class.php');
	$offers_options = new offers_options;
	
	if(!_CMS_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='add_option' and isset($_POST['name']) and $_POST['name']!='' and isset($_POST['kind'])){
			$offers_options->add($_POST);
			header('Location: ?action=offers_options');
		}elseif($_POST['action']=='edit_option' and isset($_POST['id']) and $_POST['id']>0 and isset($_POST['name']) and $_POST['name']!='' and isset($_POST['kind'])){
			$offers_options->edit($_POST['id'],$_POST);
			header('Location: ?action=offers_options');
		}
	}

	if(isset($_GET['id']) and $_GET['id']>0){
		$offers_options->getOption($_GET['id']);
	}

	$offers_options->getKinds();
	get_offers_type();
}

?>