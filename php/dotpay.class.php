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

class dotpay{
	public static function GenerateChk($DotpayId, $DotpayPin, $Environment, $RedirectionMethod, $ParametersArray,$MultiMerchantList){
		$ParametersArray['id'] = $DotpayId;
		$chk = $DotpayPin.
		(isset($ParametersArray['api_version']) ? $ParametersArray['api_version'] : null).
		(isset($ParametersArray['charset']) ? $ParametersArray['charset'] : null).
		(isset($ParametersArray['lang']) ? $ParametersArray['lang'] : null).
		(isset($ParametersArray['id']) ? $ParametersArray['id'] : null). (isset($ParametersArray['pid']) ? $ParametersArray['pid'] : null).
		(isset($ParametersArray['amount']) ? $ParametersArray['amount'] : null).
		(isset($ParametersArray['currency']) ? $ParametersArray['currency'] : null).
		(isset($ParametersArray['description']) ? $ParametersArray['description'] : null).
		(isset($ParametersArray['control']) ? $ParametersArray['control'] : null).
		(isset($ParametersArray['channel']) ? $ParametersArray['channel'] : null).
		(isset($ParametersArray['credit_card_brand']) ? $ParametersArray['credit_card_brand'] : null).
		(isset($ParametersArray['ch_lock']) ? $ParametersArray['ch_lock'] : null).
		(isset($ParametersArray['channel_groups']) ? $ParametersArray['channel_groups'] : null).
		(isset($ParametersArray['onlinetransfer']) ? $ParametersArray['onlinetransfer'] : null).
		(isset($ParametersArray['url']) ? $ParametersArray['url'] : null).
		(isset($ParametersArray['type']) ? $ParametersArray['type'] : null).
		(isset($ParametersArray['buttontext']) ? $ParametersArray['buttontext'] : null).
		(isset($ParametersArray['urlc']) ? $ParametersArray['urlc'] : null).
		(isset($ParametersArray['firstname']) ? $ParametersArray['firstname'] : null).
		(isset($ParametersArray['lastname']) ? $ParametersArray['lastname'] : null).
		(isset($ParametersArray['email']) ? $ParametersArray['email'] : null).
		(isset($ParametersArray['street']) ? $ParametersArray['street'] : null).
		(isset($ParametersArray['street_n1']) ? $ParametersArray['street_n1'] : null).
		(isset($ParametersArray['street_n2']) ? $ParametersArray['street_n2'] : null).
		(isset($ParametersArray['state']) ? $ParametersArray['state'] : null).
		(isset($ParametersArray['addr3']) ? $ParametersArray['addr3'] : null).
		(isset($ParametersArray['city']) ? $ParametersArray['city'] : null).
		(isset($ParametersArray['postcode']) ? $ParametersArray['postcode'] : null).
		(isset($ParametersArray['phone']) ? $ParametersArray['phone'] : null).
		(isset($ParametersArray['country']) ? $ParametersArray['country'] : null).
		(isset($ParametersArray['code']) ? $ParametersArray['code'] : null).
		(isset($ParametersArray['p_info']) ? $ParametersArray['p_info'] : null).
		(isset($ParametersArray['p_email']) ? $ParametersArray['p_email'] : null).
		(isset($ParametersArray['n_email']) ? $ParametersArray['n_email'] : null).
		(isset($ParametersArray['expiration_date']) ? $ParametersArray['expiration_date'] : null).
		(isset($ParametersArray['deladdr']) ? $ParametersArray['deladdr'] : null).
		(isset($ParametersArray['recipient_account_number']) ?
		$ParametersArray['recipient_account_number'] : null).
		(isset($ParametersArray['recipient_company']) ? $ParametersArray['recipient_company'] : null).
		(isset($ParametersArray['recipient_first_name']) ?
		$ParametersArray['recipient_first_name'] : null).
		(isset($ParametersArray['recipient_last_name']) ? $ParametersArray['recipient_last_name'] : null).
		(isset($ParametersArray['recipient_address_street']) ?
		$ParametersArray['recipient_address_street'] : null).
		(isset($ParametersArray['recipient_address_building']) ?
		$ParametersArray['recipient_address_building'] : null).
		(isset($ParametersArray['recipient_address_apartment']) ?
		$ParametersArray['recipient_address_apartment'] : null).
		(isset($ParametersArray['recipient_address_postcode']) ?
		$ParametersArray['recipient_address_postcode'] : null).
		(isset($ParametersArray['recipient_address_city']) ?
		$ParametersArray['recipient_address_city'] : null).
		(isset($ParametersArray['application']) ? $ParametersArray['application'] : null).
		(isset($ParametersArray['application_version']) ? $ParametersArray['application_version'] : null).
		(isset($ParametersArray['warranty']) ? $ParametersArray['warranty'] : null).
		(isset($ParametersArray['bylaw']) ? $ParametersArray['bylaw'] : null). (isset($ParametersArray['personal_data']) ? $ParametersArray['personal_data'] : null). (isset($ParametersArray['credit_card_number']) ? $ParametersArray['credit_card_number'] : null). (isset($ParametersArray['credit_card_expiration_date_year']) ?
		$ParametersArray['credit_card_expiration_date_year'] : null). (isset($ParametersArray['credit_card_expiration_date_month']) ?
		$ParametersArray['credit_card_expiration_date_month'] : null). (isset($ParametersArray['credit_card_security_code']) ?
		$ParametersArray['credit_card_security_code'] : null). (isset($ParametersArray['credit_card_store']) ? $ParametersArray['credit_card_store'] : null). (isset($ParametersArray['credit_card_store_security_code']) ?
		$ParametersArray['credit_card_store_security_code'] : null). (isset($ParametersArray['credit_card_customer_id']) ?
		$ParametersArray['credit_card_customer_id'] : null). (isset($ParametersArray['credit_card_id']) ? $ParametersArray['credit_card_id'] : null). (isset($ParametersArray['blik_code']) ? $ParametersArray['blik_code'] : null). (isset($ParametersArray['credit_card_registration']) ?
		$ParametersArray['credit_card_registration'] : null). (isset($ParametersArray['surcharge_amount']) ? $ParametersArray['surcharge_amount'] : null). (isset($ParametersArray['surcharge']) ? $ParametersArray['surcharge'] : null). (isset($ParametersArray['surcharge']) ? $ParametersArray['surcharge'] : null). (isset($ParametersArray['ignore_last_payment_channel']) ?
		$ParametersArray['ignore_last_payment_channel'] : null). (isset($ParametersArray['vco_call_id']) ? $ParametersArray['vco_call_id'] : null). (isset($ParametersArray['vco_update_order_info']) ?
		$ParametersArray['vco_update_order_info'] : null). (isset($ParametersArray['vco_subtotal']) ? $ParametersArray['vco_subtotal'] : null). (isset($ParametersArray['vco_shipping_handling']) ?
		$ParametersArray['vco_shipping_handling'] : null). (isset($ParametersArray['vco_tax']) ? $ParametersArray['vco_tax'] : null). (isset($ParametersArray['vco_discount']) ? $ParametersArray['vco_discount'] : null). (isset($ParametersArray['vco_gift_wrap']) ? $ParametersArray['vco_gift_wrap'] : null). (isset($ParametersArray['vco_misc']) ? $ParametersArray['vco_misc'] : null). (isset($ParametersArray['vco_promo_code']) ? $ParametersArray['vco_promo_code'] : null). (isset($ParametersArray['credit_card_security_code_required']) ?
		$ParametersArray['credit_card_security_code_required'] : null). (isset($ParametersArray['credit_card_operation_type']) ?
		$ParametersArray['credit_card_operation_type'] : null). (isset($ParametersArray['credit_card_avs']) ? $ParametersArray['credit_card_avs'] : null). (isset($ParametersArray['credit_card_threeds']) ? $ParametersArray['credit_card_threeds'] : null);
		foreach ($MultiMerchantList as $item)
		{
		foreach($item as $key => $value) {
		$chk = $chk.
		(isset($value) ? $value : null);
		}
		}
		return $chk;
	}

	public static function GenerateChkDotpayRedirection ($DotpayId, $DotpayPin, $Environment, $RedirectionMethod, $ParametersArray, $MultiMerchantList) {
		$ParametersArray['id'] = $DotpayId;
		$ChkParametersChain = static::GenerateChk($DotpayId, $DotpayPin, $Environment, $RedirectionMethod, $ParametersArray,$MultiMerchantList);
		$ChkValue = hash('sha256',$ChkParametersChain);
		if ($Environment == 'production') $EnvironmentAddress = 'https://ssl.dotpay.pl/t2/';
		else if ($Environment == 'test') $EnvironmentAddress = 'https://ssl.dotpay.pl/test_payment/';
		if ($RedirectionMethod == 'POST') {
		$RedirectionCode = '<form action="'.$EnvironmentAddress.'" method="POST">'.PHP_EOL;
		foreach($ParametersArray as $key => $value) {
		$RedirectionCode .= "\t".'<input name="'.$key.'" value="'.$value.'" type="hidden"/>'.PHP_EOL;
		}
		foreach ($MultiMerchantList as $item)
		{
		foreach($item as $key => $value) {
		$RedirectionCode .= "\t".'<input name="'.$key.'" value="'.$value.'" type="hidden"/>'.PHP_EOL;
		}
		}
		$RedirectionCode .= "\t".'<input name="chk" value="'.$ChkValue.'" type="hidden"/>'.PHP_EOL;
		$RedirectionCode .= '<button type="submit" class="btn btn-primary">'.lang('Pay by Dotpay').'</button></form>'.PHP_EOL;
		return $RedirectionCode;
		}
		else if ($RedirectionMethod == 'GET') {
		$RedirectionCode = $EnvironmentAddress.'?';
		foreach($ParametersArray as $key => $value) {
		$RedirectionCode .= $key.'='.rawurlencode($value).'&';
		}
		foreach ($MultiMerchantList as $item)
		{
		foreach($item as $key => $value) {
		$RedirectionCode .= $key.'='.rawurlencode($value).'&';
		}
		}
		$RedirectionCode .= 'chk='.$ChkValue;
		return $RedirectionCode;
		}
	}
}