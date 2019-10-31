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

	$limit = 100;
	
	$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'reset_password ORDER BY '.sort_by().' LIMIT :limit_from, :limit_to');
	$sth->bindValue(':limit_from', page_count('reset_password', $limit), PDO::PARAM_INT);
	$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
	$sth->execute();
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
		$sth2 = $db->prepare('SELECT username, email FROM '._DB_PREFIX_.'users where id=:user_id');
		$sth2->bindValue(':user_id', $row['user_id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['user'] = $sth2->fetch(PDO::FETCH_ASSOC);
		$sth2->closeCursor();
		$reset_password[] = $row;
	}
	$sth->closeCursor();
	if(isset($reset_password)){
		$smarty->assign("reset_password", $reset_password);
	}

}

?>