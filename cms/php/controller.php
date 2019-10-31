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
	$page = 'home';
	$title = 'CMS created by Kamil Wyremski';
	if(isset($_GET['action'])){
		switch($_GET['action']){
			case 'cms_settings': 
				$title = lang('CMS settings');
				$page = 'cms_settings';
				break;
			case 'statistics': 
				$title = lang('Statistics');
				$page = 'statistics';
				break;
			case 'offers': 
				$title = lang('Offers');
				$page = 'offers';
				break;
			case 'users': 
				$title = lang('Users');
				$page = 'users';
				break;
			case 'offers_kind': 
				$title = lang('Kinds');
				$page = 'offers_kind';
				break;
			case 'offers_state': 
				$title = lang('States');
				$page = 'offers_state';
				break;
			case 'offers_type': 
				$title = lang('Types');
				$page = 'offers_type';
				break;
			case 'offers_options': 
				$title = lang('Offers options');
				$page = 'offers_options';
				break;
			case 'offers_option': 
				$title = lang('Offers option');
				$page = 'offers_option';
				break;
			case 'index_page': 
				$title = lang('Index page');
				$page = 'index_page';
				break;
			case 'mails': 
				$title = lang('Mails');
				$page = 'mails';
				break;
			case 'articles': 
				$title = lang('Articles');
				$page = 'articles';
				break;
			case 'article': 
				$title = lang('Article');
				$page = 'article';
				break;
			case 'info': 
				$title = lang('Info');
				$page = 'info';
				break;
			case 'info_page': 
				$title = lang('Info');
				$page = 'info_page';
				break;
			case 'logs_offers': 
				$title = lang('Logs offers');
				$page = 'logs_offers';
				break;
			case 'logs_users': 
				$title = lang('Logs users');
				$page = 'logs_users';
				break;
			case 'logs_mails': 
				$title = lang('Logs mails');
				$page = 'logs_mails';
				break;
			case 'reset_password': 
				$title = lang('Reset password');
				$page = 'reset_password';
				break;
			case 'payments_dotpay': 
				$title = lang('DotPay');
				$page = 'payments_dotpay';
				break;
			case 'settings_appearance': 
				$title = lang('Appearance settings');
				$page = 'settings_appearance';
				break;
			case 'settings_social_media': 
				$title = lang('Social Media');
				$page = 'settings_social_media';
				break;
			case 'settings_ads': 
				$title = lang('Ads');
				$page = 'settings_ads';
				break;
			case 'settings_payments': 
				$title = lang('Payment settings');
				$page = 'settings_payments';
				break;
			case 'settings': 
				$title = lang('Settings');
				$page = 'settings';
				break;
		}
	}
	include('model/'.$page.'.php');
	$smarty->assign("title", $title);
	$smarty->assign('page',$page);
	$smarty->assign('settings',$settings);
	$smarty->assign('links',$links);
	$smarty->assign('_CMS_TEST_MODE_',_CMS_TEST_MODE_);
	$smarty->display('main.tpl');
}else{
	$smarty->assign('settings',$settings);
	$smarty->display('login.tpl');
}

