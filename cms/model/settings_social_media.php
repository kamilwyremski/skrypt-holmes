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

	if(!_CMS_TEST_MODE_ and isset($_POST['action']) and $_POST['action']=='save_settings_social_media' and isset($_POST['facebook_lang']) and $_POST['facebook_lang']!=''){
		
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name limit 1');

		$sth->bindValue(':value', isset($_POST['social_facebook']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_facebook', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['social_pinterest']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_pinterest', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['social_twitter']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_twitter', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['social_wykop']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_wykop', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['url_facebook'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'url_facebook', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['facebook_side_panel']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'facebook_side_panel', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['facebook_lang'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'facebook_lang', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['facebook_login']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'facebook_login', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['facebook_api'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'facebook_api', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['facebook_secret'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'facebook_secret', PDO::PARAM_STR);
		$sth->execute();
		
		get_settings();
	}
}
?>