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

	if(isset($_POST['action'])){
		if(!_CMS_TEST_MODE_ and $_POST['action']=='remove_info' and isset($_POST['id']) and $_POST['id']>0){
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'info` WHERE id=:id and page="" LIMIT 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
		}
	}

	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'info order by name');
	while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
	}
	$sth->closeCursor();
	if(isset($info )){$smarty->assign("info", $info);}
	
}

?>