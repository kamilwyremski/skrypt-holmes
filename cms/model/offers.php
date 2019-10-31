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

	include('../php/offer.class.php');
	
	$offers = new offer;
	
	if(!_CMS_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='remove_offer' and isset($_POST['id']) and $_POST['id']>0){
			$offers->remove($_POST['id']);
		}elseif($_POST['action']=='deactivate_offer' and isset($_POST['id']) and $_POST['id']>0){
			$offers->deactivate($_POST['id']);
		}elseif($_POST['action']=='activate_offer' and isset($_POST['id']) and $_POST['id']>0){
			$offers->activate($_POST['id']);
		}elseif($_POST['action']=='disable_promote_offer' and isset($_POST['id']) and $_POST['id']>0){
			$offers->disablePromote($_POST['id']);
		}elseif($_POST['action']=='enable_promote_offer' and isset($_POST['id']) and $_POST['id']>0 and isset($_POST['date']) and $_POST['date']!=''){
			$offers->enablePromote($_POST['id'],$_POST['date']);
		}elseif($_POST['action']=='remove_offers' and isset($_POST['offers']) and is_array($_POST['offers'])){
			foreach($_POST['offers'] as $key => $value){
				if($value>0){
					$offers->remove($value);
				}
			}
		}elseif($_POST['action']=='active_offers' and isset($_POST['offers']) and is_array($_POST['offers'])){
			foreach($_POST['offers'] as $key => $value){
				if($value>0){
					$offers->activate($value);
				}
			}
		}elseif($_POST['action']=='deactive_offers' and isset($_POST['offers']) and is_array($_POST['offers'])){
			foreach($_POST['offers'] as $key => $value){
				if($value>0){
					$offers->deactivate($value);
				}
			}
		}
	}

	$offers->loadOffers(50,'all_cms');
	
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'users where active = 1 order by username');
	foreach($sth as $row){$users[] = $row;}
	$sth->closeCursor();
	if(isset($users)){$smarty->assign('users', $users);}
}

?>