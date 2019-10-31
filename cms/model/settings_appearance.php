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

	if(isset($_POST['action']) and $_POST['action']=='save_settings_appearance' and isset($_POST['template']) and $_POST['template']!=''){
		
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name limit 1');

		$sth->bindValue(':value', $_POST['template'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'template', PDO::PARAM_STR);
		$sth->execute();
		
		if(!_CMS_TEST_MODE_){
			$sth->bindValue(':value', isset($_POST['search_box_kind']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_kind', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['search_box_address']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_address', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['search_box_distance']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_distance', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['search_box_keywords']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_keywords', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['search_box_price']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_price', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['search_box_state']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_state', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['search_box_type']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_type', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['search_box_options']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'search_box_options', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['logo'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'logo', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['logo_facebook'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'logo_facebook', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['watermark'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'watermark', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['favicon'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'favicon', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['rodo_alert']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'rodo_alert', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['rodo_alert_text'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'rodo_alert_text', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['footer_top'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'footer_top', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['footer_bottom'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'footer_bottom', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['code_style'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'code_style', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['code_head'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'code_head', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['code_body'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'code_body', PDO::PARAM_STR);
			$sth->execute();
		}
		
		get_settings();
		
	}
	
	// get list of templates
	$path = '../views/';
	$results = scandir($path);
	$templates = array();
	foreach ($results as $result) {
		if ($result === '.' or $result === '..') continue;
		if (is_dir($path . '/' . $result)) {
		   $templates[] .= $result;
		}
	}
	$smarty->assign("templates", $templates);
}
?>