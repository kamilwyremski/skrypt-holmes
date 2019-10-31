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
	
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'users');
	$statistics['users'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'users WHERE register_fb=1');
	$statistics['users_register_fb'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'offers');
	$statistics['offers'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'offers WHERE active=1');
	$statistics['offers_active'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'logs_mails');
	$statistics['logs_mails'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'logs_offers');
	$statistics['logs_offers'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'logs_users');
	$statistics['logs_users'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'photos');
	$statistics['photos'] = $sth->fetchColumn();

	$smarty->assign("statistics", $statistics);
}

?>