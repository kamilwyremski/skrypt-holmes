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
 
session_start();

header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL);
error_reporting(0);

ob_start();

$install = true;
include('../config/db.php');

if(isset($mysql_server)){
	header_remove();
	header('location: /cms');
	die('redirect...');
}

$settings['base_url'] = true;
include('../php/global.php');

if(isset($_GET['lang']) and $_GET['lang']!=''){
	$settings['lang'] = load_lang($_GET['lang']);
}else{
	$settings['lang'] = load_lang();
}
$list_of_lang = list_of_lang();

if(isset($_POST['base_url']) and $_POST['base_url']!='' and isset($_POST['serwer']) and $_POST['serwer']!='' and isset($_POST['user']) and $_POST['user']!='' and isset($_POST['name']) and $_POST['name']!='' and isset($_POST['user_cms']) and $_POST['user_cms']!='' and isset($_POST['password_cms']) and $_POST['password_cms']!='' and isset($_POST['password_cms_repeat']) and $_POST['password_cms_repeat']!='' and isset($_POST['email']) and $_POST['email']!='' and isset($_POST['db_prefix']) and isset($_POST['pass_hash'])){

	if($_POST['password_cms']!=$_POST['password_cms_repeat']){
		$error = lang('Error! Entered the password to CMS are different!');
	}else{
		
		define("_DB_PREFIX_", $_POST['db_prefix']);
		
		try{
			$db = new PDO('mysql:host='.$_POST['serwer'].';dbname='.$_POST['name'], $_POST['user'], $_POST['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}catch (PDOException $e){
			$error = true;
		}

		if (isset($error)) {
			$error = lang('Error! Unable to connect to the server.');
		}else{
		
			$dir = '../config/db.php';
			if (!file_exists($dir) ) {
				fwrite($dir,'');
			}else{
				chmod($dir, 0777);
			}
	 
			file_put_contents('../config/db.php', '<?php
$mysql_server = \''.$_POST['serwer'].'\';
$mysql_user = \''.$_POST['user'].'\';  
$mysql_pass = \''.$_POST['password'].'\'; 
$mysql_db = \''.$_POST['name'].'\';

define("_DB_PREFIX_", "'._DB_PREFIX_.'");
define("_PASS_HASH_", "'.$_POST['pass_hash'].'");

?>');	

			$sql = file_get_contents('holmes.sql');
			
			$sql = str_replace("CREATE TABLE IF NOT EXISTS `","CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_,$sql);
			$sql = str_replace("CREATE TABLE `","CREATE TABLE `"._DB_PREFIX_,$sql);
			$sql = str_replace("INSERT INTO `","INSERT INTO `"._DB_PREFIX_,$sql);
			$sql = str_replace("ALTER TABLE `","ALTER TABLE `"._DB_PREFIX_,$sql);

			$db->exec($sql);
			
			include('../cms/php/user_cms.php');
			$password_cms = $user_cms->createPassword($_POST['password_cms']);

			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'cms WHERE user=:user LIMIT 1');
			$sth->bindValue(':user', $_POST['user_cms'], PDO::PARAM_STR);
			$sth->execute();
			if($sth->fetchColumn()){
				$sth = $db->prepare('UPDATE '._DB_PREFIX_.'cms SET password=:password WHERE user=:user LIMIT 1');
				$sth->bindValue(':password', $password_cms, PDO::PARAM_STR);
				$sth->bindValue(':user', $_POST['user_cms'], PDO::PARAM_STR);
				$sth->execute();
			}else{
				$sth = $db->prepare('INSERT INTO '._DB_PREFIX_.'cms (`user`, `password`) VALUES (:user, :password)');
				$sth->bindValue(':user', $_POST['user_cms'], PDO::PARAM_STR);
				$sth->bindValue(':password', $password_cms, PDO::PARAM_STR);
				$sth->execute();
			}
		
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'settings SET value=:base_url WHERE name="base_url" LIMIT 1');
			$sth->bindValue(':base_url', web_address($_POST['base_url']), PDO::PARAM_STR);
			$sth->execute();
			
			$template = 'default';
			if (!file_exists('../views/'.$template) ) {
				$dirs = array_filter(glob('../views/*'), 'is_dir');
				$template = substr($dirs[0],9);
			}
			
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'settings SET value=:template WHERE name="template" LIMIT 1');
			$sth->bindValue(':template', $template, PDO::PARAM_STR);
			$sth->execute();
			
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'settings SET value=:email WHERE name="email" LIMIT 1');
			$sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
			$sth->execute();
			
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'settings SET value=:lang WHERE name="lang" LIMIT 1');
			$sth->bindValue(':lang', $settings['lang'], PDO::PARAM_STR);
			$sth->execute();
			
			chmod("../cache", 0777);
			chmod("../cms/cache", 0777);
			chmod("../cms/tmp", 0777);
			chmod("../upload/images", 0777);
			chmod("../upload/photos", 0777);
			chmod("../tmp", 0777);
			chmod("../sitemap.xml", 0777);
			chmod("../config/db.php", 0644);
			
			array_map('unlink', glob("../tmp/*"));
			array_map('unlink', glob("../cms/tmp/*"));
		
			header('location: ../cms');
			die('redirect...');
		}
	}
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
	<meta name="author" content="Kamil Wyremski - wyremski.pl">
	<title><?php echo(lang('The installer script')); ?></title>
	<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen"/>
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="engine.js"></script>
</head>
<body>
<div id="strona">
	<a href="http://wyremski.pl" title="Creating websites"><img src="../cms/images/cms.png" alt="CMS Kamil Wyremski" id="logo"/></a>
	<h2><?php echo(lang('Welcome to the installation program! Please fill in the fields below to pre-configure page.')); ?></h2>
	<?
		if(isset($error)){
			echo('<h3>'.$error.'</h3>');
		}
	?>
	<form method="get" action="">
		<h3><?php echo(lang('Select language')); ?>: <select name="lang" title="<?php echo(lang('Select language')); ?>" onchange="this.form.submit()">
				<?php
					foreach($list_of_lang as $key=>$lang){
						echo('<option value="'.$lang.'"');
						if($settings['lang']==$lang){
							echo(' selected ');
						}
						echo('>'.$lang.'</option>');
					}
				?>
			</select>
		</h3>
	</form>
	<form method="post" action="" id="form_install">
		<table>
			<tr>
				<td><?php echo(lang('Base URL')); ?></td>
				<td><input type="text" name="base_url" placeholder="<?php echo(lang('Base URL')); ?>" value="<?php if(isset($_POST['base_url'])){echo($_POST['base_url']);}?>" required title="<?php echo(lang('Enter Web site address')); ?>"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('The database server')); ?>:</td>
				<td><input type="text" name="serwer" placeholder="<?php echo(lang('The database server')); ?>" value="<?php if(isset($_POST['serwer'])){echo($_POST['serwer']);}else{echo('localhost');}?>" required title="<?php echo(lang('Enter the address of the database server - default: localhost')); ?>"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('The database user name')); ?>:</td>
				<td><input type="text" name="user" placeholder="<?php echo(lang('The database user name')); ?>" value="<?php if(isset($_POST['user'])){echo($_POST['user']);}?>" required title="<?php echo(lang('Enter the name of the database user')); ?>"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('The database name')); ?>:</td>
				<td><input type="text" name="name" placeholder="<?php echo(lang('The database name')); ?>" value="<?php if(isset($_POST['name'])){echo($_POST['name']);}?>" required title="<?php echo(lang('Enter the name of the database')); ?>"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('Password for database')); ?>:</td>
				<td><input type="password" name="password" placeholder="<?php echo(lang('Password for database')); ?>" value="<?php if(isset($_POST['password'])){echo($_POST['password']);}?>" title="<?php echo(lang('Enter the password to the database')); ?>"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('Login to CMS')); ?>:</td>
				<td><input type="text" name="user_cms" placeholder="<?php echo(lang('Login to CMS')); ?>" value="<?php if(isset($_POST['user_cms'])){echo($_POST['user_cms']);}else{echo('administrator');}?>" required title="<?php echo(lang('Enter the login which you want to log in to the CMS - default: administrator')); ?>"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('Password to CMS')); ?>:</td>
				<td><span class="red"><?php echo(lang('The passwords are different')); ?></span><input type="password" name="password_cms" placeholder="<?php echo(lang('Password to CMS')); ?>" value="<?php if(isset($_POST['password_cms'])){echo($_POST['password_cms']);}?>" required title="<?php echo(lang('Enter the password to CMS')); ?>" /></td>
			</tr>
			<tr>
				<td><?php echo(lang('Repeat password to CMS')); ?>:</td>
				<td><input type="password" name="password_cms_repeat" placeholder="<?php echo(lang('Repeat password to CMS')); ?>" required title="<?php echo(lang('Here you enter the password again')); ?>"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('E-mail Administrator')); ?>:</td>
				<td><input type="email" name="email" placeholder="<?php echo(lang('E-mail Administrator')); ?>" value="<?php if(isset($_POST['email'])){echo($_POST['email']);}?>" title="<?php echo(lang('Enter e-mail the site administrator')); ?>" required/></td>
			</tr>
			<tr>
				<td><?php echo(lang('Prefix tables in the database')); ?>:</td>
				<td><input type="text" name="db_prefix" placeholder="<?php echo(lang('Prefix tables in the database')); ?>" value="<?php if(isset($_POST['db_prefix'])){echo($_POST['db_prefix']);}?>" title="<?php echo(lang('In cases where multiple sites use the one database you can determine the prefix table, otherwise leave it blank')); ?>" pattern="[a-z_]*"/></td>
			</tr>
			<tr>
				<td><?php echo(lang('Salt passwords in the system')); ?>:</td>
				<td><input type="text" name="pass_hash" placeholder="<?php echo(lang('Salt passwords in the system')); ?>" value="<?php if(isset($_POST['pass_hash'])){echo($_POST['pass_hash']);}?>" title="<?php echo(lang('Additional security user passwords - enter any string')); ?>"/></td>
			</tr>
		</table>
		<input type="submit" value="<?php echo(lang('Save')); ?>"/>
	</form>
	<p style="text-align: left"><?php echo(lang('In case of problems with the installation, change the permissions of the following files and folders on the value of 0777')); ?>:
	<br>/cache
	<br>/cms/cache
	<br>/cms/tmp
	<br>/upload/images
	<br>/upload/photos
	<br>/tmp
	<br>/sitemap.xml
	<br>/config/db.php - <?php echo(lang('in the last file after the installation is completed, change for 0644')); ?></p>
</div>
<br><br><br>
<footer>CMS v3.7 Copyright and project © 2014 - 2017 by <a href="http://wyremski.pl" target="_blank" title="Creating websites">Kamil Wyremski</a>. All rights reserved.</footer>
</body>
</html>
