<nav id="top" class="container-fluid">
	<p class="text-right small"><a href="https://wyremski.pl/skrypt/holmes" title="Holmes - script" target="_blank">Holmes v1.4</a></p>
</nav>
<nav class="navbar navbar-default navbar-fixed-top" id="menu_box">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{$settings.base_url}" title="{$settings.title}">{if $settings.logo}<img src="{$settings.logo}" alt="{$settings.title}">{else}{$settings.title}{/if}</a>
    </div>
    <div class="collapse navbar-collapse" id="menu">
		<ul class="nav navbar-nav navbar-right">
			<li class="hidden-xs">
				<p class="navbar-btn"><a href="{$links.add}" title="{'Add offer'|lang}" class="btn btn-warning" style="margin-right: 20px">{'Add offer'|lang}</a></p>
			</li>
			<li class="menu_link {if $page=='index'}active{/if}"><a href="{$settings.base_url}" title="{$settings.title}">{'Home'|lang}</a></li>
			<li class="visible-xs-block menu_link {if $page=='add'}active{/if}">
				<a href="{$links.add}" title="{'Add offer'|lang}">{'Add offer'|lang}</a>
			</li>
			<li class="menu_link {if $page=='offers'}active{/if}"><a href="{$links.offers}" title="{'Search the best offers'|lang}">{'Offers'|lang}</a></li>
			{if $settings.enable_articles}<li class="menu_link {if $page=='articles'}active{/if}"><a href="{$links.articles}" title="{'Articles'|lang}">{'Articles'|lang}</a></li>{/if}
			<li class="menu_link {if $page=='info'} active{/if}"><a href="{$links.info}" title="{'Info about us'|lang}">{'Info'|lang}</a></li>
			{if $user->logged_in}
				<li class="dropdown menu_link">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="{'My account'|lang}">{'Account'|lang} <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="{$links.add}" title="{'Add offer'|lang}">{'Add offer'|lang}</a></li>
						<li><a href="{$links.my_offers}" title="{'My offers'|lang}">{'My offers'|lang}</a></li>
						<li><a href="{$links.settings}" title="{'Settings'|lang}">{'Settings'|lang}</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="?logOut" title="{'Log out of the system'|lang}">{'Log out'|lang}</a></li>
					</ul>
				</li>
			{else}
				<li class="menu_link"><a href="{$links.register}" title="{'Registration on the website'|lang}">{'Registration'|lang}</a></li>
				<li class="hidden-xs">
					<p class="navbar-btn"><a href="{$links.login}" title="{'Log in on the website'|lang}" class="btn btn-primary">{'Log in'|lang}</a></p>
				</li>
				<li class="visible-xs-block menu_link {if $page=='log'}active{/if}">
					<a href="{$links.login}" title="{'Log in on the website'|lang}">{'Log in'|lang}</a>
				</li>
			{/if}
		</ul>
    </div>
  </div>
</nav>


