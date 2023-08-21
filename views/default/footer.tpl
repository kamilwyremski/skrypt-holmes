
{if $settings.ads_4}<div class="ads">{$settings.ads_4}</div>{/if}

<footer>
	<div id="footer_top">
		<br>
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h3>{$settings.title}</h3>
					{$settings.footer_top}
					<br><br>
				</div>
				<div class="col-md-6">
					<h3>{'Sitemap'|lang}</h3>
					<ul class="list-unstyled row">
						<li><a class="col-xs-6" href="{$settings.base_url}" title="{$settings.title}">{'Home'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.add}" title="{'Add offer'|lang}">{'Add offer'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.offers}" title="{'Search the best offers'|lang}">{'Offers'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.my_offers}" title="{'My offers'|lang}">{'My offers'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.login}" title="{'Log in on the website'|lang}">{'Log in'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.register}" title="{'Registration on the website'|lang}">{'Registration'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.reset_password}" title="{'Reset password'|lang}">{'Reset password'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.info}/2,{$settings.url_rules}" title="{'Regulamin serwisu'|lang}">{'Regulamin serwisu'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.info}/1,{$settings.url_privacy_policy}" title="{'Privacy policy'|lang}">{'Privacy policy'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.contact}" title="{'Contact with us'|lang}">{'Contact'|lang}</a></li>
						<li><a class="col-xs-6" href="{$links.info}" title="{'Info about us'|lang}">{'Info'|lang}</a></li>
						{if $settings.enable_articles}<li><a class="col-xs-6" href="{$links.articles}" title="{'Articles'|lang}">{'Articles'|lang}</a></li>{/if}
						{if $settings.rss}<li><a class="col-xs-6" href="php/rss.php{if isset($smarty.get.search) && isset($pagination) && $pagination.page_url.page}?{$pagination.page_url.page}{/if}" title="{'RSS feed'|lang}" target="_blank">{'RSS feed'|lang}</a></li>{/if}
					</ul>
					<br><br>
				</div>
			</div>
		</div>
	</div>
	<div id="footer_bottom" class="text-center">
		<!-- MIT licensed script. Deleting software author information is prohibited -->
		{$settings.footer_bottom}
		{$settings.footer_text}
	</div>
</footer>

<a href="#" title="{'Back to top'|lang}" id="back_to_top" class="back_to_top_hidden"><img src="{$settings.base_url}/views/{$settings.template}/images/back_to_top.png" alt="Back to top"></a>
{if $settings.facebook_side_panel}
	<div id="facebook2_2" class="hidden-xs">
		<div id="facebook2_2_image"><img src="{$settings.base_url}/views/{$settings.template}/images/facebook-side.png" alt="Facebook" width="10" height="21"></div>
		<div class="fb-page" data-href="{$settings.url_facebook}" data-width="300" data-height="350" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"></div>
	</div>
{/if}

<div id="fb-root"></div>
<script>(function(d, s, id) {ldelim}
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/{$settings.facebook_lang|default:en_US}/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
{rdelim}(document, 'script', 'facebook-jssdk'));
</script>
