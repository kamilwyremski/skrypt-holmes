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

if($user_cms->logged_in and isset($_POST['select_1']) and isset($_POST['select_2']) and isset($_POST['date_from']) and isset($_POST['date_to'])){

	function plot_select($select){
		global $db;
		$result = array();
		switch($select){
			case 'logins': 
				$sth = $db->prepare('SELECT date, count(1) as number FROM '._DB_PREFIX_.'logs_users WHERE date>=:date_from and date<=:date_to group by date(date)');
				break;
			case 'unique_logins': 
				$sth = $db->prepare('SELECT date, count(1) as number from (select id, date from '._DB_PREFIX_.'logs_users where date>=:date_from and date<=:date_to group by date(date), user_id) t1 group by date(date)');
				break;
			case 'registration': 
				$sth = $db->prepare('SELECT date, count(1) as number FROM '._DB_PREFIX_.'users WHERE date>=:date_from and date<=:date_to group by date(date)');
				break;
			case 'activation_users': 
				$sth = $db->prepare('SELECT activation_date as date, count(1) as number FROM '._DB_PREFIX_.'users WHERE activation_date>=:date_from and activation_date<=:date_to group by date(activation_date)');
				break;
			case 'offers': 
				$sth = $db->prepare('SELECT date, count(1) as number FROM '._DB_PREFIX_.'offers WHERE date>=:date_from and date<=:date_to group by date(date)');
				break;
			case 'views_offers': 
				$sth = $db->prepare('SELECT date, count(1) as number FROM '._DB_PREFIX_.'logs_offers WHERE date>=:date_from and date<=:date_to group by date(date)');
				break;
			default:
				$sth = $db->prepare('SELECT date, count(1) as number FROM '._DB_PREFIX_.'logs_offers WHERE 1=2 :date_from :date_to');
		}
		$sth->bindValue(':date_from', $_POST['date_from'], PDO::PARAM_STR);
		$sth->bindValue(':date_to', $_POST['date_to']." 23:59", PDO::PARAM_STR);
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$data[0] = $row['date'];
			$data[1] = (int) $row['number'];
			$result[] = $data;
		}
		if(empty($result)){
			$result[] = array();
		}
		return $result;
	}

	$statistics = array();
	$statistics[] = plot_select($_POST['select_1']);
	$statistics[] = plot_select($_POST['select_2']);
	echo (json_encode($statistics));
}

?>