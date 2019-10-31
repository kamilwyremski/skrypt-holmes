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

require_once('../config/config.php');
require_once('user.class.php');
require_once('offer.class.php');

if($_SERVER['REMOTE_ADDR']=='195.150.9.37' && !empty($_POST)){

	$dotpay_id = trim($_POST['id']);
	$offer_id = trim($_POST['control']);
	$email = trim($_POST['email']);
	$description = trim($_POST['description']);
	$operation_type = trim($_POST['operation_type']);
	$operation_status = trim($_POST['operation_status']);
	$operation_amount = trim($_POST['operation_amount']);
	$operation_original_amount = trim($_POST['operation_original_amount']);
	$signature = trim($_POST['signature']);
	$operation_datetime = trim($_POST['operation_datetime']);
	$operation_number = trim($_POST['operation_number']);
	$sign = hash('sha256', $settings['dotpay_pin'].$_POST['id'].$_POST['operation_number'].$_POST['operation_type'].$_POST['operation_status'].$_POST['operation_amount'].$_POST['operation_currency'].$_POST['operation_withdrawal_amount'].$_POST['operation_commission_amount'].$_POST['operation_original_amount'].$_POST['operation_original_currency'].$_POST['operation_datetime'].$_POST['operation_related_number'].$_POST['control'].$_POST['description'].$_POST['email'].$_POST['p_info'].$_POST['p_email'].$_POST['credit_card_issuer_identification_number'].$_POST['credit_card_masked_number'].$_POST['credit_card_brand_codename'].$_POST['credit_card_brand_code'].$_POST['credit_card_id'].$_POST['channel'].$_POST['channel_country'].$_POST['geoip_country']);

	if($operation_type == 'payment' and $signature == $sign and $settings['dotpay_id'] == $dotpay_id){

		$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'payments_dotpay WHERE operation_number=:operation_number AND operation_status="completed" LIMIT 1');
		$sth->bindValue(':operation_number', $operation_number, PDO::PARAM_STR);
		$sth->execute();
		if(!$sth->fetchColumn()){

			$sth = $db->prepare('INSERT INTO '._DB_PREFIX_.'payments_dotpay (dotpay_id, operation_status, operation_number, offer_id, operation_amount, operation_original_amount, email, description, operation_datetime, date_URLC) VALUES (:dotpay_id, :operation_status, :operation_number, :offer_id, :operation_amount, :operation_original_amount, :email, :description, :operation_datetime, NOW())');
			$sth->bindValue(':dotpay_id', $dotpay_id, PDO::PARAM_STR);
			$sth->bindValue(':operation_status', $operation_status, PDO::PARAM_STR);
			$sth->bindValue(':operation_number', $operation_number, PDO::PARAM_STR);
			$sth->bindValue(':offer_id', $offer_id, PDO::PARAM_INT);
			$sth->bindValue(':operation_amount', $operation_amount, PDO::PARAM_STR);
			$sth->bindValue(':operation_original_amount', $operation_original_amount, PDO::PARAM_STR);
			$sth->bindValue(':email', $email, PDO::PARAM_STR);
			$sth->bindValue(':description', $description, PDO::PARAM_STR);
			$sth->bindValue(':operation_datetime', $operation_datetime, PDO::PARAM_STR);
			$sth->execute();

			if($operation_status == "completed"){

				$offer = new offer;
				$offer->loadOffer($offer_id);
				if(!$offer->promoted){
					send_mail('start_promote',$offer->email,array('offer_name'=>$offer->name, 'offer_url'=>$offer->id.','.$offer->name_url, 'user_id'=>$user->getId()));
				}
				$offer->enablePromote($offer_id);

			}
		}
		echo "OK";
	}else{
		echo "ERROR";
	}
}
