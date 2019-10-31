

{if isset($articles)}
	<ul class="media-list main-list articles_list">
		{foreach from=$articles item=item key=key}
			<li class="media">
				<a href="{$links.article}/{$item.id},{$item.name_url}" class="pull-left">
					<img src="{if $item.thumb}{$item.thumb}{else}{$settings.base_url}/views/{$settings.template}/images/no_image.png{/if}" alt="{$item.name}" class="media-object thumbnail img-responsive" onerror="this.src='{$settings.base_url}/views/{$settings.template}/images/no_image.png'">
				</a>
				<div class="media-body">
					<h4 class="media-heading"><a href="{$links.article}/{$item.id},{$item.name_url}">{$item.name}</a></h4>
					<p class="text-muted small">{$item.date|date_format:"%d-%m-%Y"}</p>
					<p>{$item.content_short}</p>
				</div>
			</li>
		{/foreach}
	</ul>
{else}
	<h3 class="text-danger">{'Nothing found'|lang}</h3>
{/if}


