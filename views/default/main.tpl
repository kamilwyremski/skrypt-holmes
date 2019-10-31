<!doctype html>
<html lang="{$settings.lang}">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="Keywords" content="{$settings.seo_keywords|default:$settings.keywords}">
	<meta name="Description" content="{$settings.seo_description|default:$settings.description}">
	<title>{$settings.seo_title|default:$settings.title}</title>
	<base href="{$settings.base_url}/">

	<link rel="stylesheet" href="views/{$settings.template}/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="views/{$settings.template}/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="views/{$settings.template}/css/style.css"/>
	{if $settings.favicon}<link rel="shortcut icon" href="{$settings.favicon}">{/if}
	{if $settings.code_style}<style>{$settings.code_style}</style>{/if}
	
	{if $settings.logo_facebook}<meta property="og:image" content="{$settings.logo_facebook|get_full_url}">{/if}
	<meta property="og:description" content="{$settings.seo_description|default:$settings.description}">
	<meta property="og:title" content="{$settings.seo_title|default:$settings.title}">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="{$settings.seo_title|default:$settings.title}">
	<meta property="og:locale" content="{$settings.facebook_lang}">
	{if $settings.facebook_api}<meta property="fb:app_id" content="{$settings.facebook_api}">{/if}

	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="views/{$settings.template}/js/bootstrap-select.min.js"></script>
	<script src="views/{$settings.template}/js/whcookies.js"></script>
	<script src="views/{$settings.template}/js/engine.js"></script>
	{$settings.code_head}
</head>
<body>

	{include file="$page.html"}
	
	{$settings.analytics}
	
	{$settings.code_body}
	
	{if $settings.google_maps}
		<script>
			{literal}
				function initGoogle() {
					if ( typeof displayMap == 'function' ) { 
						displayMap();
					}else{
						autocomplete = new google.maps.places.Autocomplete((document.getElementById('search_main_address')), {types: ['geocode']});
					}
				}
			{/literal}
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key={$settings.google_maps_api}&v=3.exp&libraries=places&callback=initGoogle" async defer></script>
	{/if}
	
	<div id="cookies-message-container"><div id="cookies-message">{'This site uses cookies, so that our service may work better.'|lang}<a href="javascript:WHCloseCookiesWindow();" id="accept-cookies-checkbox">{'I accept'|lang}</a></div></div>
	
	{if $settings.rodo_alert}
		<div id="rodo-message" class="modal fade" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-body">
				{$settings.rodo_alert_text}
			  </div>
			  <div class="modal-footer">
				<a href="javascript:closeRodoWindow();" class="btn btn-primary">{'I agree to the processing my personal data'|lang}</a>
			  </div>
			</div>
		  </div>
		</div>
	{/if}
</body>
</html>
