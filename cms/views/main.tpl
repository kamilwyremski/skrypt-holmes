<!doctype html>
<html lang="{$settings.lang}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Keywords" content="CMS, Content Management System">
	<meta name="Description" content="A content management system is a computer application that supports the creation and modification of digital content using a common user interface and thus usually supporting multiple users working in a collaborative environment. Created by Kamil Wyremski - wyremski.pl">
	<meta name="author" content="Kamil Wyremski">
	<title>{$title|default:'CMS created by Kamil Wyremski'}</title>
	
	<link href='https://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Ubuntu+Condensed&subset=latin,latin-ext' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Oregano&subset=latin,latin-ext' rel='stylesheet'>
	<link rel="stylesheet" href="views/css/jquery-ui.min.css">
    <link rel="stylesheet" href="views/css/global.css">
	<link rel="stylesheet" href="views/css/slimmenu.css">
	<link rel="stylesheet" href="views/css/style.css">
	<link rel="shortcut icon" href="images/favicon.ico"/>
	
	<script src="js/jquery-2.2.3.min.js"></script> 
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/jquery.slimmenu.min.js"></script> 
	<script src="js/ckeditor/ckeditor.js"></script>
	<script>
		lang = "{$settings.lang|escape:'javascript'}";
		base_url = "{$settings.base_url|escape:'javascript'}";
	</script>
    <script src="js/engine_cms.js"></script>
	<script src="js/whcookies.js"></script>
</head>
<body>
<aside id="left">
	<div id="top_menu" class="center">
		<a href="http://wyremski.pl" title="Web Design" target="_blank"><img src="images/cms.png" alt="Logo" id="logo"/></a><br>
		<a href="?action=cms_settings" title="{'CMS settings'|lang}"><span class="icon icon_settings"></span>{'CMS settings'|lang}</a><br>
		<a href="?logOut" title="{'Log out'|lang}"><span class="icon icon_padlock"></span>{'Log out'|lang}</a>
	</div>
	<nav id="menu">
		<ul class="slimmenu">
			<li><a href="index.php" title="Home" class="link"><span class="icon icon_ok2"></span>HOME</a></li>
			<li><a href="?action=statistics" title="{'Statistics'|lang}" class="link"><span class="icon icon_watch"></span>{'Statistics'|lang}</a></li>
			<li><a href="?action=offers" title="{'Offers'|lang}" class="link green_menu"><span class="icon icon_documents"></span>{'Offers'|lang}</a></li>
			<li><a href="?action=users" title="{'Users'|lang}" class="link green_menu"><span class="icon icon_face"></span>{'Users'|lang}</a></li>
			<li><span class="link"><span class="icon icon_hanger"></span>{'Additional'|lang}</span>
				<ul><li><a href="?action=offers_kind" title="{'Kinds'|lang}">{'Kinds'|lang}</a></li>
					<li><a href="?action=offers_state" title="{'States'|lang}">{'States'|lang}</a></li>
					<li><a href="?action=offers_type" title="{'Types'|lang}">{'Types'|lang}</a></li>
					<li><a href="?action=offers_options" title="{'Offers Options'|lang}">{'Offers options'|lang}</a></li>
				</ul>
			</li>
			<li><span class="link"><span class="icon icon_pen"></span>{'Contents'|lang}</span>
				<ul><li><a href="?action=index_page" title="{'Index page'|lang}">{'Index page'|lang}</a></li>
					<li><a href="?action=mails" title="{'Mails'|lang}">{'Mails'|lang}</a></li>
					<li><a href="?action=info" title="{'Info'|lang}">{'Info'|lang}</a></li>
					<li><a href="?action=articles" title="{'Articles'|lang}">{'Articles'|lang}</a></li>
				</ul>
			</li>
			<li><span class="link"><span class="icon icon_drawer"></span>{'Logs'|lang}</span>
				<ul><li><a href="?action=logs_offers" title="{'Logs offers'|lang}">{'Offers'|lang}</a></li>
					<li><a href="?action=logs_users" title="{'Logs users'|lang}">{'Users'|lang}</a></li>
					<li><a href="?action=logs_mails" title="{'Logs mails'|lang}">{'Mails'|lang}</a></li>
					<li><a href="?action=reset_password" title="{'Reset password'|lang}">{'Reset password'|lang}</a></li>
				</ul>
			</li>
			<li><span class="link"><span class="icon icon_padlock"></span>{'Logs payments'|lang}</span>
				<ul><li><a href="?action=payments_dotpay" title="{'DotPay'|lang}">{'DotPay'|lang}</a></li></ul>
			</li>
			<li><a href="?action=settings" title="{'Settings'|lang}" class="link"><span class="icon icon_settings"></span>{'Settings'|lang}</a>
				<ul>
					<li><a href="?action=settings_appearance" title="{'Appearance'|lang}">{'Appearance'|lang}</a></li>
					<li><a href="?action=settings_social_media" title="{'Social Media'|lang}">{'Social Media'|lang|truncate:20:'...':true}</a></li>
					<li><a href="?action=settings_ads" title="{'Ads'|lang}">{'Ads'|lang}</a></li>
					<li><a href="?action=settings_payments" title="{'Payment settings'|lang}">{'Payment settings'|lang}</a></li>
					<li><a href="?action=settings" title="{'General settings'|lang}">{'General settings'|lang}</a></li>
				</ul>
			</li>
		</ul>
	</nav>
	<footer class="center">
		<p>CMS v3.7 Copyright and project © 2016 by <a href="http://wyremski.pl" target="_blank" title="Web Design">Kamil Wyremski</a></p>
	</footer>
</aside>
<section id="page">
	{if $_CMS_TEST_MODE_}<p class="center"><b>{'Demo version of the CMS. Most of the editing functions are disabled'|lang}</b></p><br>{/if}
	
	{include file="$page.html"}
	
</section>
<footer class="center" id="footer_bottom">
	<p>CMS v3.7 Copyright and project © 2016 by <a href="http://wyremski.pl" target="_blank" title="Web Design">Kamil Wyremski</a></p>
</footer>
{include file="cookies.tpl"}
</body>
</html>
