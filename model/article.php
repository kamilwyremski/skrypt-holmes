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

if(isset($_GET['id']) and $_GET['id']>0 and isset($_GET['name_url']) and $_GET['name_url']!=''){
	
	$sth = $db->prepare('select * from '._DB_PREFIX_.'articles where id=:id limit 1');
	$sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
	$sth->execute();
	$article = $sth->fetch(PDO::FETCH_ASSOC);
	$sth->closeCursor();
	if($article!=''){
		if($_GET['name_url']!=$article['name_url']){
			header("Location: ".$settings['base_url'].'/'.$links['article']."/".$article['id'].','.$article['name_url']);
			die('redirect');
		}else{
			$smarty->assign("article", $article);
			$settings['seo_title'] = $article['name'].' - '.$settings['title'];
			if($article['description']){
				$settings['seo_description'] = $article['description'];
			}else{
				$settings['seo_description'] = $article['name'].' - '.$settings['description'];
			}
			if($article['keywords']){
				$settings['seo_keywords'] = $article['keywords'];
			}
			if($article['thumb']){$settings['logo_facebook'] = $article['thumb'];}
		}
	}else{
		include('model/404.php');
	}
	
}else{
	include('model/404.php');
}

?>