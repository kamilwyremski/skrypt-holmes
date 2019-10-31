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

	if(!_CMS_TEST_MODE_ and isset($_POST['action']) and $_POST['action']=='save_settings' and isset($_POST['base_url']) and $_POST['base_url']!='' and isset($_POST['email']) and $_POST['email']!='' and isset($_POST['title']) and $_POST['title']!=''){
		
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name limit 1');

		$sth->bindValue(':value', web_address($_POST['base_url']), PDO::PARAM_STR);
		$sth->bindValue(':name', 'base_url', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['email'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'email', PDO::PARAM_STR);
		$sth->execute();
		if($settings['lang']!=$_POST['lang']){
			unset($links, $translate);
			$sth->bindValue(':value', load_lang($_POST['lang']), PDO::PARAM_STR);
			$sth->bindValue(':name', 'lang', PDO::PARAM_STR);
			$sth->execute();
		}
		$sth->bindValue(':value', $_POST['title'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'title', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['keywords'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'keywords', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['description'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'description', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['analytics'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'analytics', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['add_offers_not_logged']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'add_offers_not_logged', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['automatically_activate_offers']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'automatically_activate_offers', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['enable_articles']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'enable_articles', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['rss']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'rss', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['generate_sitemap']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'generate_sitemap', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['check_ip_user']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'check_ip_user', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['google_maps']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'google_maps', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_maps_api'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'google_maps_api', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_maps_api2'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'google_maps_api2', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_maps_lat'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'google_maps_lat', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_maps_zoom_add'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'google_maps_zoom_add', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_maps_zoom_offer'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'google_maps_zoom_offer', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_maps_long'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'google_maps_long', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['limit_page'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'limit_page', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['limit_page_index'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'limit_page_index', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['show_similar_offer']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'show_similar_offer', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['limit_similar_offer'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'limit_similar_offer', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['photo_add']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'photo_add', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['photo_max'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'photo_max', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['photo_max_size'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'photo_max_size', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['photo_max_height'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'photo_max_height', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['photo_max_width'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'photo_max_width', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['photo_quality'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'photo_quality', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['watermark_add']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'watermark_add', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['hide_data_not_logged']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'hide_data_not_logged', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['hide_phone']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'hide_phone', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['hide_email']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'hide_email', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['hide_views']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'hide_views', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['mail_attachment']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'mail_attachment', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['smtp']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'smtp', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['smtp_host'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'smtp_host', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['smtp_mail'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'smtp_mail', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['smtp_user'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'smtp_user', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['smtp_password'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'smtp_password', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['smtp_port'], PDO::PARAM_INT);
		$sth->bindValue(':name', 'smtp_port', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['smtp_secure'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'smtp_secure', PDO::PARAM_STR);
		$sth->execute();
		
		get_settings();
	}
}

$smarty->assign("list_of_lang", list_of_lang());
?>