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

class user_cms {
	
	public function __construct () {
		global $db, $settings;
		$this->logged_in = false;
		
		if(isset($_GET['logOut'])){
			
			$this->logOut();
			header("Location: ".$settings['base_url'].'/cms');
			die('redirect');
	
		}elseif(isset($_SESSION['user_cms']['user']) and isset($_SESSION['user_cms']['session_code'])){
			
			$sth = $db->prepare('select 1 from '._DB_PREFIX_.'cms_session where user=:user and code=:code limit 1');
			$sth->bindValue(':user', $_SESSION['user_cms']['user'], PDO::PARAM_STR);
			$sth->bindValue(':code', $_SESSION['user_cms']['session_code'], PDO::PARAM_STR);
			$sth->execute();
			
			if($sth->fetchColumn()){
				$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'cms WHERE user=:user limit 1');
				$sth->bindValue(':user', $_SESSION['user_cms']['user'], PDO::PARAM_STR);
				$sth->execute();
				$user = $sth->fetch(PDO::FETCH_ASSOC);
				if($user!=''){
					$this->user_data = $user;
					$this->logged_in = true;
				}
			}else{
				unset($_SESSION['user_cms']);
			}
		}
		if(!$this->logged_in){
			$this->newSessionCode();
		}
	}
	
	public function __get($value){
		return $this->user_data[$value];
	}
	
	public function login($data){
		global $db, $settings;
		$sth = $db->prepare('select 1 from '._DB_PREFIX_.'cms_session where code=:code and ip=:ip limit 1');
		$sth->bindValue(':code', $data['session_code'], PDO::PARAM_STR);
		$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
		$sth->execute();
		if($sth->fetchColumn()){
			
			$sth = $db->prepare('select * from '._DB_PREFIX_.'cms where user=:user and password=:password limit 1');
			$sth->bindValue(':user', $data['user'], PDO::PARAM_STR);
			$sth->bindValue(':password', $this->createPassword($data['password']), PDO::PARAM_STR);
			$sth->execute();
		
			$user = $sth->fetch(PDO::FETCH_ASSOC);
		
			if($user!=''){

				$_SESSION['user_cms']['user'] = $user['user'];
				$_SESSION['user_cms']['session_code'] = $data['session_code'];
				
				$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'cms_session` SET user=:user WHERE code=:code');
				$sth->bindValue(':user', $user['user'], PDO::PARAM_STR);
				$sth->bindValue(':code', $data['session_code'], PDO::PARAM_STR);
				$sth->execute();
				$sth->closeCursor();
				
				$this->logged_in = true;
				
				$this->save_logs(1,$user['user']);

			}else{
				show_info('data_incorrect');
				$this->removeSessionCode($data['session_code']);
				$this->save_logs(0,$data['user']);
			}
		}else{
			show_info("session_error");
			$this->save_logs(0,$data['user']);
		}
	}
	

	public function newSessionCode(){
		global $db, $smarty;
		$this->logOut();
		$session_code = md5(uniqid(rand(), true));		
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'cms_session`(`code`, `ip`, `date`) VALUES (:code,:ip,NOW())');
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
		$sth->execute();
		if(isset($smarty)){$smarty->assign("session_code", $session_code);}
	}
	
	public function removeSessionCode($session_code){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'cms_session` WHERE code=:code');
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->execute();
	}
	
	public function logOut(){
		$this->logged_in = false;
		unset($_SESSION['user_cms']);
	}
	
	public function createPassword($text){
		return md5($text);
	}
	
	public function save_logs($logged=0,$user=''){
		global $db;
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'cms_logs`(`user`, `logged`, `ip`, `date`) VALUES (:user, :logged, :ip,NOW())');
		$sth->bindValue(':user', $user, PDO::PARAM_STR);
		$sth->bindValue(':logged', $logged, PDO::PARAM_INT);
		$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
		$sth->execute();
		$sth->closeCursor();
	}
	
	public function changePassword($data){
		global $db, $smarty;
		if($data['new_password']==$data['repeat_new_password']){
			if($data['new_username']!=$this->user){
				$sth = $db->prepare('select 1 from '._DB_PREFIX_.'cms where user=:user limit 1');
				$sth->bindValue(':user', $data['new_username'], PDO::PARAM_STR);
				$sth->execute();
				if($sth->fetchColumn()){
					$smarty->assign("info", lang('The selected username is already taken'));
					return false;
				}
			}
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'cms SET user=:new_username, password=:password where user=:user limit 1');
			$sth->bindValue(':new_username', $data['new_username'], PDO::PARAM_STR);
			$sth->bindValue(':password', $this->createPassword($data['new_password']), PDO::PARAM_STR);
			$sth->bindValue(':user', $this->user, PDO::PARAM_STR);
			$sth->execute();
			
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'cms_session SET user=:new_username where code=:code limit 1');
			$sth->bindValue(':new_username', $data['new_username'], PDO::PARAM_STR);
			$sth->bindValue(':code', $_SESSION['user_cms']['session_code'], PDO::PARAM_STR);
			$sth->execute();
			$_SESSION['user_cms']['user'] = $data['new_username'];
			
			$smarty->assign("info", lang('The data have been updated'));
		}else{
			$smarty->assign("error", lang('Entered passwords are different.'));
		}
	}
}

$user_cms = new user_cms();

if(isset($_POST['action']) and $_POST['action'] == 'login' and isset($_POST['session_code']) and $_POST['session_code']!='' and isset($_POST['user']) and $_POST['user']!='' and isset($_POST['password']) and $_POST['password']!=''){
	
	$user_cms->login($_POST);
	
}

if(isset($smarty)){$smarty->assign("user_cms", $user_cms);}

?>