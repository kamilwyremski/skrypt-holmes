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

	if(!_CMS_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='save_index_page' and isset($_POST['text_1'])){
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'index_page` SET value=:text_1 WHERE name="text_1" LIMIT 1');
			$sth->bindValue(':text_1', $_POST['text_1'], PDO::PARAM_STR);
			$sth->execute();
		}elseif($_POST['action']=='add_slide'){
			$db->exec('INSERT INTO `'._DB_PREFIX_.'slider`() VALUES ()');
		}elseif($_POST['action']=='save_slide' and isset($_POST['content']) and isset($_POST['id']) and $_POST['id']>0){
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'slider` SET content=:content WHERE id=:id LIMIT 1');
			$sth->bindValue(':content', $_POST['content'], PDO::PARAM_STR);
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
		}elseif($_POST['action']=='remove_slide' and isset($_POST['id']) and $_POST['id']>0){
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'slider` WHERE id=:id limit 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
		}
	}
	
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'index_page');
	foreach($sth as $row){
		$index_page[$row['name']] = $row['value'];
	}
	$sth->closeCursor();
	$smarty->assign("index_page", $index_page);
	
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'slider');
	foreach($sth as $row){
		$slider[] = $row;
	}
	$sth->closeCursor();
	if(isset($slider)){$smarty->assign("slider", $slider);}
	
}

?>