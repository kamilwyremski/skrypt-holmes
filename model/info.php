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
	
	$sth = $db->prepare('select * from '._DB_PREFIX_.'info where id=:id limit 1');
	$sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
	$sth->execute();
	$info_page = $sth->fetch(PDO::FETCH_ASSOC);
	$sth->closeCursor();
	if($info_page!=''){
		if($_GET['name_url']!=$info_page['name_url']){
			header("Location: ".$settings['base_url'].'/'.$links['info']."/".$info_page['id'].','.$info_page['name_url']);
			die('redirect');
		}else{
			$smarty->assign("info_page", $info_page);
			$settings['seo_title'] = $info_page['name'].' - '.$settings['title'];
			if($info_page['description']){
				$settings['seo_description'] = $info_page['description'];
			}else{
				$settings['seo_description'] = $info_page['name'].' - '.$settings['description'];
			}
			if($info_page['keywords']){
				$settings['seo_keywords'] = $info_page['keywords'];
			}
		}
	}else{
		include('model/404.php');
	}
	
}else{
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'info order by name');
	while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
	}
	$sth->closeCursor();
	$smarty->assign("info", $info);
	$settings['seo_title'] = lang('Info').' - '.$settings['title'];
	$settings['seo_description'] = lang('Info').' - '.$settings['description'];
}

?>