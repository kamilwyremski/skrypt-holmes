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
 
require_once(realpath(dirname(__FILE__)).'/config/config.php');

function cron(){
	global $settings, $db;

	$db->query('DELETE FROM '._DB_PREFIX_.'cms_session WHERE date<(CURDATE() - INTERVAL 1 DAY)');
	
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'photos WHERE offer_id=0 and date<(CURDATE() - INTERVAL 1 DAY)');
	foreach($sth as $row){
		unlink(realpath(dirname(__FILE__)).'/upload/photos/'.$row['thumb']);
		unlink(realpath(dirname(__FILE__)).'/upload/photos/'.$row['url']);
	}
	$db->query('DELETE FROM '._DB_PREFIX_.'photos WHERE offer_id=0 and date<(CURDATE() - INTERVAL 1 DAY)');
	
	$db->query('UPDATE '._DB_PREFIX_.'reset_password SET active=0 WHERE active=1 and date<(CURDATE() - INTERVAL 1 DAY)');

	$db->query('DELETE FROM '._DB_PREFIX_.'users WHERE active=0 and date<(CURDATE() - INTERVAL 1 DAY)');
	
	$db->query('DELETE FROM '._DB_PREFIX_.'session_offer WHERE date<(CURDATE() - INTERVAL 1 DAY)');
	
	$db->query('DELETE FROM '._DB_PREFIX_.'session_user WHERE date<(CURDATE() - INTERVAL 1 DAY)');
	
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'offers WHERE promoted=1 and promoted_date_end<CURDATE()');
	foreach($sth as $row){
		send_mail('finish_promote',$row['email'],array('offer_name'=>$row['name'], 'offer_url'=>$row['id'].','.$row['name_url']));
	}
	$db->query('UPDATE '._DB_PREFIX_.'offers SET promoted=0 WHERE promoted=1 and promoted_date_end<CURDATE()');

}
cron();

if($settings['generate_sitemap']){
	include(realpath(dirname(__FILE__)).'/php/sitemap_generator.php');
	sitemap_generator();
}

