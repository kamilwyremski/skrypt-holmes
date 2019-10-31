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

	if(!_CMS_TEST_MODE_ and isset($_POST['action']) and $_POST['action'] == 'change_password' and isset($_POST['new_username']) and $_POST['new_username']!='' and isset($_POST['new_password']) and $_POST['new_password']!='' and isset($_POST['repeat_new_password']) and $_POST['repeat_new_password']!=''){
		
		$user_cms->changePassword($_POST);
		
	}
	
	if(!_CMS_TEST_MODE_ and isset($_POST['action']) and $_POST['action'] == 'cms_remove_logs'){
		$db->query('DELETE FROM '._DB_PREFIX_.'cms_logs');
	}else{
		$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'cms_logs order by date desc');
		while($row = $sth->fetch(PDO::FETCH_ASSOC)) {$cms_logs[] = $row;}
		$sth->closeCursor();
		if(isset($cms_logs )){$smarty->assign("cms_logs", $cms_logs);}
	}
}

?>