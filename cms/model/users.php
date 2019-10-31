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

include('../php/offer.class.php');
include('../php/user.class.php');

if($user_cms->logged_in){

	if(!_CMS_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='remove_user' and isset($_POST['id']) and $_POST['id']>0){
			$user->remove($_POST['id']);
		}elseif($_POST['action']=='remove_users' and isset($_POST['users']) and is_array($_POST['users'])){
			foreach($_POST['users'] as $key => $value){
				if($value>0){
					$user->remove($value);
				}
			}
		}
	}


	$limit = 50;
	$where_statement = ' true ';
	if(isset($_GET['search'])){
		if(isset($_GET['username']) and $_GET['username']!=''){
			$where_statement .= ' and username like "%'.filter($_GET['username']).'%"  ';
		}
		if(isset($_GET['email']) and $_GET['email']!=''){
			$where_statement .= ' and email like "%'.filter($_GET['email']).'%"  ';
		}
		if(isset($_GET['active'])){
			if($_GET['active']=='yes'){
				$where_statement .= ' and active="1" ';
			}elseif($_GET['active']=='no'){
				$where_statement .= ' and active="0" ';
			}
		}
	}

	$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE '.$where_statement.' ORDER BY '.sort_by().' LIMIT :limit_from, :limit_to');
	$sth->bindValue(':limit_from', page_count('users', $limit, $where_statement), PDO::PARAM_INT);
	$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
	$sth->execute();
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
		$sth2 = $db->prepare('SELECT COUNT(*) FROM '._DB_PREFIX_.'offers WHERE user_id=:user_id');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['amount_offers'] = $sth2->fetchColumn();
		
		$sth2 = $db->prepare('SELECT COUNT(*) FROM '._DB_PREFIX_.'offers WHERE user_id=:user_id AND active=1');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['amount_active_offers'] = $sth2->fetchColumn();
		
		$sth2 = $db->prepare('SELECT date FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id ORDER BY date desc LIMIT 1');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['last_login'] = $sth2->fetchColumn();
		
		$sth2 = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$rows = $sth2->fetchAll();
		$row['amount_logins'] = count($rows);
		
		$users[] = $row;
	}
	$sth->closeCursor();

	if(isset($users)){$smarty->assign("users", $users);}
}

?>