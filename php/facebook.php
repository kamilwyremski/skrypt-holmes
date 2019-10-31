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

require_once( 'libs/facebook/HttpClients/FacebookHttpable.php' );
require_once( 'libs/facebook/HttpClients/FacebookCurl.php' );
require_once( 'libs/facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( 'libs/facebook/HttpClients/FacebookStreamHttpClient.php' );
require_once( 'libs/facebook/HttpClients/FacebookStream.php' );
require_once( 'libs/facebook/FacebookSession.php' );
require_once( 'libs/facebook/FacebookRedirectLoginHelper.php' );
require_once( 'libs/facebook/FacebookRequest.php' );
require_once( 'libs/facebook/FacebookResponse.php' );
require_once( 'libs/facebook/FacebookSDKException.php' );
require_once( 'libs/facebook/FacebookRequestException.php' );
require_once( 'libs/facebook/FacebookServerException.php');
require_once( 'libs/facebook/FacebookOtherException.php' );
require_once( 'libs/facebook/FacebookAuthorizationException.php' );
require_once( 'libs/facebook/GraphObject.php' );
require_once( 'libs/facebook/GraphLocation.php' );
require_once( 'libs/facebook/GraphUser.php' );
require_once( 'libs/facebook/GraphSessionInfo.php' );
require_once( 'libs/facebook/Entities/AccessToken.php');


// Called class with namespace
use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookServerException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphLocation;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;

FacebookSession::setDefaultApplication($settings['facebook_api'],$settings['facebook_secret']);

$helper = new FacebookRedirectLoginHelper($settings['base_url'].'/'.$links['login']); 

try {
	$session = $helper->getSessionFromRedirect();
}catch( FacebookRequestException $ex ) {
}catch( Exception $ex ) {
}

$email = '';
if(isset($session)){
	$request = new FacebookRequest( $session, 'GET', '/me?fields=email' );
	$response = $request->execute();
	$object = $response->getGraphObject();
	$email_fb = $object->getProperty('email');
	if($email_fb!=''){
		$email = $user->loginByFB($email_fb);
	}
}

if(!$email){
	$smarty->assign("url_facebook", $helper->getLoginUrl(array('scope' => 'email')));
}

?>