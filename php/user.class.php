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

class user {
	
	public function __construct () {
		global $db, $settings;
		$this->logged_in = false;
		
		if(isset($_GET['logOut'])){
			
			$this->logOut();
			header("Location: ".$settings['base_url']);
			die('redirect');
	
		}elseif(isset($_SESSION['user']['id']) and isset($_SESSION['user']['session_code'])){
			
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'session_user WHERE user_id=:user_id AND code=:code LIMIT 1');
			$sth->bindValue(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
			$sth->bindValue(':code', $_SESSION['user']['session_code'], PDO::PARAM_STR);
			$sth->execute();
			
			if($sth->fetchColumn()){
				$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE id=:id LIMIT 1');
				$sth->bindValue(':id', $_SESSION['user']['id'], PDO::PARAM_INT);
				$sth->execute();
				$user = $sth->fetch(PDO::FETCH_ASSOC);
				if($user!=''){
					$this->user_data = $user;
					$this->logged_in = true;
				}
			}else{
				unset($_SESSION['user']);
			}
		}
	}
	
	public function __get($value){
		if(isset($this->user_data[$value])){
			return $this->user_data[$value];
		}
		return false;
	}
	
	public function login($data){
		global $db, $settings, $links;
		if($settings['check_ip_user']){
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'session_user WHERE code=:code AND ip=:ip LIMIT 1');
			$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
		}else{
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'session_user WHERE code=:code LIMIT 1');
		}
		$sth->bindValue(':code', $data['session_code'], PDO::PARAM_STR);
		$sth->execute();
		if($sth->fetchColumn()){
			
			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE (username=:username or email=:username) AND password=:password LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->bindValue(':password', $this->createPassword($data['password']), PDO::PARAM_STR);
			$sth->execute();
		
			$user = $sth->fetch(PDO::FETCH_ASSOC);
			
			if($user!=''){
				if($user['active']=='1'){
					if($user['username']==''){
						header("Location: ".$links['login']."?complete_data=".$user['activation_code']);
						die('redirect');
					}
					
					$_SESSION['user']['id'] = $user['id'];
					$_SESSION['user']['session_code'] = $data['session_code'];
					
					$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'session_user` SET user_id=:user_id WHERE code=:code');
					$sth->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
					$sth->bindValue(':code', $data['session_code'], PDO::PARAM_STR);
					$sth->execute();
					$sth->closeCursor();
					
					$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_users`(`user_id`, `ip`, `date`) VALUES (:user_id,:ip,NOW())');
					$sth->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
					$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
					$sth->execute();
					$sth->closeCursor();

					if(isset($_GET['redirect']) and $_GET['redirect']!=''){
						header("Location: ".$_GET['redirect']."");
					}else{
						header("Location: ".$settings['base_url']);
					}
					die('redirect');
				}else{
					show_info('no_active');
					$this->removeSessionCode($data['session_code']);
				}
			}else{
				show_info('data_incorrect');
				$this->removeSessionCode($data['session_code']);
			}
		}else{
			show_info('session_error');
		}
	}
	
	public function checkCodeAndActivate($activation_code){
		global $db, $smarty;
		$sth = $db->prepare('SELECT id FROM '._DB_PREFIX_.'users WHERE active=0 AND activation_code=:activation_code LIMIT 1');
		$sth->bindValue(':activation_code', $activation_code, PDO::PARAM_STR);
		$sth->execute();
		$id = $sth->fetch(PDO::FETCH_ASSOC)['id'];
		if($id){
			$this->activate($id);
			return true;
		}else{
			return false;
		}
	}
	
	public function activate($id){
		global $db;
		$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users SET active=1, activation_ip=:activation_ip, activation_date=NOW() WHERE id=:id LIMIT 1');
		$sth->bindValue(':activation_ip', get_client_ip(), PDO::PARAM_STR);
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
	public function setModerator($id){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'users` SET moderator=1 WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
	public function unSetModerator($id){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'users` SET moderator=0 WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
	public function newSessionCode(){
		global $db, $smarty;
		$this->logOut();
		$session_code = md5(uniqid(rand(), true));		
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'session_user`(`user_id`, `code`, `ip`, `date`) VALUES (0, :code,:ip,NOW())');
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
		$sth->execute();
		if(isset($smarty)){$smarty->assign("session_code", $session_code);}
	}
	
	public function removeSessionCode($session_code){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'session_user` WHERE code=:code');
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->execute();
	}
	
	public function logOut(){
		$this->logged_in = false;
		unset($_SESSION['user']);
	}
	
	public function register($data){
		global $smarty, $db, $links, $settings;

		if($data['captcha']!=$_SESSION['captcha']){
			$error['captcha'] = lang('Invalid captcha code.');
		}else{
			if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL) or strlen($data['email'])>64) {
				$error['email'] = lang('Incorrect e-mail address.');
			}else{
				$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE email=:email LIMIT 1');
				$sth->bindValue(':email', $data['email'], PDO::PARAM_STR);
				$sth->execute();
				if($sth->fetchColumn()){
					$error['email'] = lang('E-mail already exists in the database.');
				}
			}
			$data['username'] = name_url(strip_tags($data['username']));
			if(!$data['username'] or (strpos($data['username'],'@') !== false) or strlen($data['username'])>64){
				$error['username'] = lang('Invalid login.');
			}else{
				$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE username=:username LIMIT 1');
				$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
				$sth->execute();
				if($sth->fetchColumn()){
					$error['username'] = lang('The selected username is already taken');
				}
			}
			if(!$data['password'] or strlen($data['password'])>32){
				$error['password'] = lang('The password is incorrect.');
			}elseif($data['password']!=$data['password_repeat']){
				$error['password'] = lang('Entered passwords are different.');
			}
			if(!isset($data['rules'])){
				$error['rules'] = lang('This field is mandatory.');
			}
		}
		
		if(isset($error)){
			$smarty->assign("error", $error);
		}else{
		
			$activation_code = md5(uniqid(rand(), true));
			
			send_mail('register',$data['email'],array('activation_code'=>$activation_code, 'password'=>$data['password'], 'username'=>$data['username'], 'email'=>$data['email']));

			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'users`(`username`, `email`, `password`, `active`, `activation_code`, `register_ip`, `date`) VALUES (:username,:email,:password,0,:activation_code,:register_ip,NOW())');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->bindValue(':email', $data['email'], PDO::PARAM_STR);
			$sth->bindValue(':password', $this->createPassword($data['password']), PDO::PARAM_STR);
			$sth->bindValue(':activation_code', $activation_code, PDO::PARAM_STR);
			$sth->bindValue(':register_ip', get_client_ip(), PDO::PARAM_STR);
			$sth->execute();
			
			header("Location: ".$links['login']."?info=new_account");
			die('redirect');
		}
	}
	
	public function resetPassword($data){
		global $smarty, $db, $settings, $links;
		
		if($data['captcha']!=$_SESSION['captcha']){
			$smarty->assign("error", lang('Invalid captcha code.'));
		}else{
			$sth = $db->prepare('SELECT id, email, username FROM '._DB_PREFIX_.'users WHERE (username=:username or email=:username) LIMIT 1');
			$sth->bindValue(':username', strip_tags($data['username']), PDO::PARAM_STR);
			$sth->execute();
			$user_data = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();
			if($user_data!=''){
				$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'reset_password WHERE active=1 AND date>(NOW() - INTERVAL 1 DAY) AND user_id=:user_id LIMIT 1');
				$sth->bindValue(':user_id', $user_data['id'], PDO::PARAM_INT);
				$sth->execute();
				if($sth->fetchColumn()){
					$smarty->assign("error", lang('Link to change your password has been sent.'));
				}else{
					$code = md5(uniqid(rand(), true));
					
					$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'reset_password`(`user_id`, `active`, `code`, `date`) VALUES (:user_id,1,:code,NOW())');
					$sth->bindValue(':user_id', $user_data['id'], PDO::PARAM_INT);
					$sth->bindValue(':code', $code, PDO::PARAM_STR);
					$sth->execute();
					
					send_mail('reset_password',$user_data['email'], array('reset_password_code'=>$code, 'username'=>$user_data['username']));
					
					header("Location: ".$links['login']."?info=mail_password_send");
					die('redirect');
				}
			}else{
				$smarty->assign("error", lang('Incorrect user data.'));
			}
		}
	}
	
	public function resetPasswordNew($code){
		global $db, $smarty;
		
		$sth = $db->prepare('SELECT user_id FROM '._DB_PREFIX_.'reset_password WHERE active=1 AND date>(NOW() - INTERVAL 1 DAY) AND code=:code LIMIT 1');
		$sth->bindValue(':code', $code, PDO::PARAM_STR);
		$sth->execute();
		$user_id = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();
		if($user_id!=''){
			return $user_id;
		}
		return false;
	}
	
	public function resetPasswordNewCheck($user_id,$data,$code){
		global $db, $smarty, $links, $settings;
		
		if($data['password']!=$data['password_repeat']){
			$error = lang('Entered passwords are different.');
		}elseif($data['password']=='' or strlen($data['password'])>32){
			$error = lang('The password is incorrect.');
		}
		if(isset($error)){
			$smarty->assign("error", $error);
		}else{
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'reset_password set used=1, active=0 WHERE code=:code LIMIT 1');
			$sth->bindValue(':code', $code, PDO::PARAM_STR);
			$sth->execute();
			$sth->closeCursor();
			
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users set password=:password WHERE id=:id LIMIT 1');
			$sth->bindValue(':password', $this->createPassword($data['password']), PDO::PARAM_STR);
			$sth->bindValue(':id', $user_id, PDO::PARAM_INT);
			$sth->execute();
			$sth->closeCursor();

			header("Location: ".$settings['base_url'].'/'.$links['login']."?info=password_change");
			die('redirect');
		}
	}
	
	public function createPassword($text){
		return md5($text._PASS_HASH_);
	}
	
	public function checkCompleteData($code){
		global $db;
		$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE ISNULL(username) AND active=1 AND activation_code=:code LIMIT 1');
		$sth->bindValue(':code', $code, PDO::PARAM_STR);
		$sth->execute();
		if($sth->fetchColumn()){
			return true;
		}
		return false;
	}
	
	public function completeData($code,$data){
		global $db, $settings, $smarty;
		if(!$data['username'] or (strpos($data['username'],'@') !== false) or strlen($data['username'])>64){
			$error['username'] = lang('Invalid login.');
		}else{
			$data['username'] = name_url(strip_tags($data['username']));
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE username=:username LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->execute();
			if($sth->fetchColumn()){
				$error['username'] = lang('The selected username is already taken');
			}
		}
		if(isset($error)){
			$smarty->assign("error", $error);
		}else{
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users SET username=:username WHERE active=1 AND activation_code=:code AND ISNULL(username) LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->bindValue(':code', $code, PDO::PARAM_STR);
			$sth->execute();

			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE username=:username LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->execute();
			$user = $sth->fetch(PDO::FETCH_ASSOC);

			$session_code = md5(uniqid(rand(), true));	
			
			$_SESSION['user']['id'] = $user['id'];
			$_SESSION['user']['session_code'] = $session_code;
			
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'session_user`(`user_id`, `code`, `ip`, `date`) VALUES (:user_id,:code,:ip,NOW())');
			$sth->bindValue(':user_id', $user['id'], PDO::PARAM_STR);
			$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
			$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
			$sth->execute();
			
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_users`(`user_id`, `ip`, `date`) VALUES (:user_id,:ip,NOW())');
			$sth->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
			$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
			$sth->execute();
			$sth->closeCursor();
			
			if(isset($_GET['redirect']) and $_GET['redirect']!=''){
				header("Location: ".$_GET['redirect']."");
			}else{
				header("Location: ".$settings['base_url']);
			}
			die('redirect');

		}
	}
	
	public function getAllData(){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'offers WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['number_offers'] = $sth->rowCount();
		$sth->closeCursor();
		
		$sth = $db->prepare('SELECT date FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id order by date desc LIMIT 1,1');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['last_login'] = $sth->fetch(PDO::FETCH_ASSOC)['date'];
		$sth->closeCursor();
		
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['number_login'] = $sth->rowCount();
		$sth->closeCursor();
		
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'reset_password WHERE user_id=:user_id AND user=1 order by date desc LIMIT 1');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['last_reset_password'] = $sth->fetch(PDO::FETCH_ASSOC)['date'];
		$sth->closeCursor();
	}
	
	public function changePassword($data){
		global $db, $smarty;
		if($data['new_password']==$data['repeat_new_password']){
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE id=:id AND password=:password LIMIT 1');
			$sth->bindValue(':id', $this->id, PDO::PARAM_INT);
			$sth->bindValue(':password', $this->createPassword($data['old_password']), PDO::PARAM_STR);
			$sth->execute();
			if($sth->fetchColumn()){
				$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users SET password=:password WHERE id=:id LIMIT 1');
				$sth->bindValue(':id', $this->id, PDO::PARAM_INT);
				$sth->bindValue(':password', $this->createPassword($data['new_password']), PDO::PARAM_STR);
				$sth->execute();
				$smarty->assign("info", lang('The password has been correctly updated'));
			}else{
				$smarty->assign("error", lang('Enter proper old password'));
			}
		}else{
			$smarty->assign("error", lang('Entered passwords are different.'));
		}
	}
	
	public function saveDescription($description){
		global $db;
		$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users SET description=:description WHERE id=:id LIMIT 1');
		$sth->bindValue(':description', $description, PDO::PARAM_STR);
		$sth->bindValue(':id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['description'] = $description;
	}
	
	public function getId(){
		if($this->logged_in){
			return $this->id;
		}
		return 0;
	}
	
	public function remove($id){
		global $db;
		$offers = new offer;
		$sth = $db->prepare('SELECT id FROM '._DB_PREFIX_.'offers WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $id, PDO::PARAM_INT);
		$sth->execute();
		foreach($sth as $row){;
			$offers->remove($row['id']);
		}
		$sth->closeCursor();
		$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth->closeCursor();
		$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'reset_password WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth->closeCursor();
		$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'session_user WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth->closeCursor();
		$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'users WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth->closeCursor();
	}
	
	public function loginByFB($email){
		global $db, $links, $settings;
		
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE email=:email LIMIT 1');
		$sth->bindValue(':email', $email, PDO::PARAM_STR);
		$sth->execute();
		$user_fb = $sth->fetch(PDO::FETCH_ASSOC);

		if($user_fb!=''){
			
			if($user_fb['active']=='0'){
				$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'users` SET active=1 WHERE id=:id');
				$sth->bindValue(':id', $user_fb['id'], PDO::PARAM_INT);
				$sth->execute();
				$sth->closeCursor();
			}
			if($user_fb['username']==''){
				header("Location: ".$links['login']."?complete_data=".$user_fb['activation_code']);
				die('redirect');
			}
			
			$session_code = md5(uniqid(rand(), true));	
			
			$_SESSION['user']['id'] = $user_fb['id'];
			$_SESSION['user']['session_code'] = $session_code;
			
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'session_user`(`user_id`, `code`, `ip`, `date`) VALUES (:user_id,:code,:ip,NOW())');
			$sth->bindValue(':user_id', $user_fb['id'], PDO::PARAM_STR);
			$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
			$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
			$sth->execute();
			
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_users`(`user_id`, `ip`, `date`) VALUES (:user_id,:ip,NOW())');
			$sth->bindValue(':user_id', $user_fb['id'], PDO::PARAM_INT);
			$sth->bindValue(':ip', get_client_ip(), PDO::PARAM_STR);
			$sth->execute();
			$sth->closeCursor();

			if(isset($_GET['redirect']) and $_GET['redirect']!=''){
				header("Location: ".$_GET['redirect']."");
			}else{
				header("Location: ".$settings['base_url']);
			}
			die('redirect');
		}else{
			
			$activation_code = md5(uniqid(rand(), true));
			$password = randomPassword();
			
			send_mail('register_fb',$email,array('activation_code'=>$activation_code, 'password'=>$password, 'email'=>$email));

			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'users`(`active`, `email`, `password`, `activation_code`, `register_fb`, `register_ip`, `activation_date`, `activation_ip`, `date`) VALUES (1, :email,:password,:activation_code,1,:register_ip,NOW(), :activation_ip, NOW())');
			$sth->bindValue(':email', strip_tags($email), PDO::PARAM_STR);
			$sth->bindValue(':password', $this->createPassword($password), PDO::PARAM_STR);
			$sth->bindValue(':activation_code', $activation_code, PDO::PARAM_STR);
			$sth->bindValue(':register_ip', get_client_ip(), PDO::PARAM_STR);
			$sth->bindValue(':activation_ip', get_client_ip(), PDO::PARAM_STR);
			$sth->execute();
			
			header("Location: ".$links['login']."?complete_data=".$activation_code);
			die('redirect');
		}
	}
	
	public function getProfile($username){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE active=1 AND username=:username LIMIT 1');
		$sth->bindValue(':username', $username, PDO::PARAM_STR);
		$sth->execute();
		$profile = $sth->fetch(PDO::FETCH_ASSOC);
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'offers WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $profile['id'], PDO::PARAM_INT);
		$sth->execute();
		$profile['number_offers'] = $sth->rowCount();
		$sth->closeCursor();
		return ($profile);
	}
}

$user = new user();
if(isset($smarty)){$smarty->assign("user", $user);}

?>