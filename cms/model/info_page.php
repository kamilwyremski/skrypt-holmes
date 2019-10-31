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
		if($_POST['action']=='add_info' and isset($_POST['name']) and $_POST['name']!=''){
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'info`(`name`, `name_url`, `content`, `keywords`, `description`) VALUES (:name, :name_url, :content, :keywords, :description)');
			$sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
			$sth->bindValue(':name_url', name_url($_POST['name']), PDO::PARAM_STR);
			$sth->bindValue(':content', $_POST['content'], PDO::PARAM_STR);
			$sth->bindValue(':keywords', $_POST['keywords'], PDO::PARAM_STR);
			$sth->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
			$sth->execute();
			$id = $db->lastInsertId();
			header('Location: ?action=info_page&id='.$id);
			die('redirect');
		}elseif($_POST['action']=='edit_info' and isset($_POST['id']) and $_POST['id']>0 and isset($_POST['name']) and $_POST['name']!=''){
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'info` SET `name`=:name, `name_url`=:name_url, `content`=:content, `keywords`=:keywords, `description`=:description WHERE id=:id limit 1');
			$sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
			$sth->bindValue(':name_url', name_url($_POST['name']), PDO::PARAM_STR);
			$sth->bindValue(':content', $_POST['content'], PDO::PARAM_STR);
			$sth->bindValue(':keywords', $_POST['keywords'], PDO::PARAM_STR);
			$sth->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
			if($_POST['id']==1){
				$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET `value`=:value WHERE name="url_privacy_policy" LIMIT 1');
				$sth->bindValue(':value', name_url($_POST['name']), PDO::PARAM_STR);
				$sth->execute();
			}elseif($_POST['id']==2){
				$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET `value`=:value WHERE name="url_rules" LIMIT 1');
				$sth->bindValue(':value', name_url($_POST['name']), PDO::PARAM_STR);
				$sth->execute();
			}
		}
	}

	if(isset($_GET['id']) and $_GET['id']>0){
		$sth = $db->prepare('select * from '._DB_PREFIX_.'info where id=:id LIMIT 1');
		$sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
		$sth->execute();
		$info_page = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();
		if($info_page!=''){
			$title = $info_page['name'].' - '.lang('Info');
			$smarty->assign("info_page", $info_page);
		}
	}

}

?>