<?php
/************************************************************************
 * The script of real estate HOLMES v 1.4
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

require_once('config/config.php');

require_once('libs/smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = 'views/'.$settings['template'];
$smarty->compile_dir = 'tmp';
$smarty->cache_dir = 'cache';

require_once('php/user.class.php');
require_once('php/offer.class.php');
require_once('php/offers_options.class.php');
require_once('php/controller.php');

$smarty->assign("settings", $settings);
$smarty->assign('page',$page);
$smarty->assign('links',$links);

$smarty->display('main.tpl');
 
