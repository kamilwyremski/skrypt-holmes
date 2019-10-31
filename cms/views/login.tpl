<!doctype html>
<html lang="{$settings.lang}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Keywords" content="CMS, Content Management System">
	<meta name="Description" content="A content management system is a computer application that supports the creation and modification of digital content using a common user interface and thus usually supporting multiple users working in a collaborative environment. Created by Kamil Wyremski - wyremski.pl">
	<meta name="author" content="Kamil Wyremski">
	<title>{$title|default:'CMS created by Kamil Wyremski'}</title>
	
	<link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Condensed&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Oregano&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="views/css/global.css">
	<link rel="stylesheet" type="text/css" href="views/css/style.css">
	<link rel="shortcut icon" href="images/favicon.ico"/>
	
	<script type="text/javascript" src="js/whcookies.js"></script>
</head>
<body>
<div id="login_window" class="center">	
	<a href="http://wyremski.pl" title="Creating websites" target="_blank"><img src="images/cms.png" alt="Logo Wyremski.pl"/></a>
	<form method="post" action="">
		<input type="hidden" name="action" value="login">
		<input type="hidden" name="session_code" value="{$session_code}">
		<h2>{'login panel'|lang}</h2>
		{if isset($error)}<p class="red">{$error}</p>{/if}
		<input type="text" name="user" placeholder="{'Username'|lang}" maxlength="32" required title="{'Enter your username'|lang}"><br>
		<input type="password" name="password" placeholder="****" maxlength="32" required title="{'Enter your password'|lang}"><br>
		<input type="submit" value="{'LOG IN !'|lang}"/>
	</form>
</div>
{include file="cookies.tpl"}
</body>
</html>
