
{foreach from=$offers item=item key=key name=offers}
	{if $smarty.foreach.offers.index%3 == 0} 
		<div class="row">
	{/if}
	<div class="col-md-4 text-center">
		<div class="offers_index overflow_hidden {if $item.promoted}offers_index_promoted{/if}" itemscope itemtype="https://schema.org/RealEstateAgent">
			<a href="{$item.id},{$item.name_url}" title="{$item.name}" itemprop="url"><img src="{if $item.thumb}upload/photos/{$item.thumb}{else}{$settings.base_url}/views/{$settings.template}/images/no_image.png{/if}" alt="{$item.name}" onerror="this.src='{$settings.base_url}/views/{$settings.template}/images/no_image.png'" itemprop="image"></a>
			<h4><a href="{$item.id},{$item.name_url}" title="{$item.name}"><span itemprop="name">{$item.name}</span></a></h4>
			<p>{if !empty($item.kind.name)}<a href="{$links.offers}/?search&kind%5B%5D={$item.kind.name_url}" title="{'Kind'|lang}: {$item.kind.name}">{$item.kind.name}</a>{/if}{if !empty($item.kind.name) && !empty($item.state.name)} | {/if}{if !empty($item.state.name)}<a href="{$links.offers}/?search&state%5B%5D={$item.state.name_url}" title="{'State'|lang}: {$item.state.name}"><span itemprop="address">{$item.state.name}</span></a>{/if}</p>
			<p class="text-muted" itemprop="disambiguatingDescription">{$item.description|strip_tags|truncate:150}</p>
		</div>
	</div>
	{if $smarty.foreach.offers.index%3 == 2} 
		</div>
	{/if}
{/foreach}
{if count($offers)%3 != 0}</div>{/if}

	
	