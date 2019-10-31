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

$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'slider');
foreach($sth as $row){
	$slider[] = $row['content'];
}
$sth->closeCursor();
if(isset($slider)){$smarty->assign("slider", $slider);}

$offers = new offer;
$offers->loadOffers($settings['limit_page_index']);

if($settings['search_box_kind']){
	get_offers_kinds();
}
if($settings['search_box_state']){
	get_offers_state();
}
if($settings['search_box_type']){
	get_offers_type();
}

$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'index_page');
while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
	$index_page[$row['name']] = $row['value'];
}
$sth->closeCursor();
$smarty->assign("index_page", $index_page);

if($settings['enable_articles']){
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'articles order by date desc limit 5');
	while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
		$articles[] = $row;
	}
	$sth->closeCursor();
	if(isset($articles )){$smarty->assign("articles", $articles);}
}

?>