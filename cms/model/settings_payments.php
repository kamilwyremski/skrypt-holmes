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

	if(!_CMS_TEST_MODE_ and isset($_POST['action']) and $_POST['action']=='save_settings_payments'){

		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name limit 1');

		$sth->bindValue(':value', $_POST['currency'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'currency', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['promote_by_dotpay']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'promote_by_dotpay', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['promote_days'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'promote_days', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['promote_cost'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'promote_cost', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['dotpay_id'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'dotpay_id', PDO::PARAM_STR);
		$sth->execute();
    	$sth->bindValue(':value', $_POST['dotpay_pin'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'dotpay_pin', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['dotpay_currency'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'dotpay_currency', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['dotpay_test_mode']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'dotpay_test_mode', PDO::PARAM_STR);
		$sth->execute();

		get_settings();
	}
}
