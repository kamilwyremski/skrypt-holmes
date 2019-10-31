
{if $settings.ads_side_1}<div class="ads">{$settings.ads_side_1}</div>{/if}

<form class="form-horizontal form-search" method="get" id="form_search_offers">
	<input type="hidden" name="search">
	{if isset($smarty.get.username)}
		<div class="form-group">
			<label for="username" class="control-label">{'Username'|lang}: </label>
			<select name="username" id="username" class="form-control">
				<option value="">{'All users'|lang}</option>
				<option value="{$smarty.get.username}" selected>{$smarty.get.username}</option>
			</select>
		</div>
	{/if}
	{if $settings.search_box_keywords}
		<div class="form-group">
			<label for="keywords" class="control-label">{'Keywords'|lang}: </label>
			<input class="form-control" type="text" name="keywords" id="keywords" placeholder="{'Enter your keywords...'|lang}" title="{'Enter your keywords...'|lang}" {if isset($smarty.get.keywords)}value="{$smarty.get.keywords}"{/if}>
		</div>
	{/if}
	{if isset($offers_kind)}
		<div class="form-group check_all_parent">
			<label for="kind" class="control-label">{'Kind'|lang}:</label>
			<div class="checkbox">
				<label><input type="checkbox" class="check_all" {if !isset($smarty.get.kind) or empty($smarty.get.kind)}checked{/if}>{'All'|lang}</label>
			</div>
			<div class="group_checkbox" {if !isset($smarty.get.kind) or empty($smarty.get.kind)}style="display:none"{/if}>
				{foreach from=$offers_kind item=item key=key}
					<div class="checkbox">
						<label><input type="checkbox" name="kind[]" value="{$item.name_url}" {if isset($smarty.get.kind) && is_array($smarty.get.kind) && $item.name_url|in_array:$smarty.get.kind}checked{/if}>{$item.name}</label>
					</div>
				{/foreach}
			</div>
		</div>
	{/if}
	{if isset($offers_type)}
		<div class="form-group check_all_parent">
			<label for="type" class="control-label">{'Offer type'|lang}:</label>
			<div class="checkbox">
				<label><input type="checkbox" class="check_all checkbox_select_type_all" {if !isset($smarty.get.type) or empty($smarty.get.type)}checked{/if}>{'All'|lang}</label>
			</div>
			<div class="group_checkbox" {if !isset($smarty.get.type) or empty($smarty.get.type)}style="display:none"{/if}>
				{foreach from=$offers_type item=item key=key}
					<div class="checkbox">
						<label><input type="checkbox" name="type[]" value="{$item.name_url}" {if isset($smarty.get.type) && is_array($smarty.get.type) && $item.name_url|in_array:$smarty.get.type}checked{/if} class="checkbox_select_type" data-id="{$item.id}">{$item.name}</label>
					</div>
				{/foreach}
			</div>
		</div>
	{/if}
	{if isset($offers_state)}
		<div class="form-group check_all_parent">
			<label for="state" class="control-label">{'State'|lang}:</label>
			<div class="checkbox">
				<label><input type="checkbox" class="check_all" {if !isset($smarty.get.state) or empty($smarty.get.state)}checked{/if}>{'All'|lang}</label>
			</div>
			<div class="group_checkbox" {if !isset($smarty.get.state) or empty($smarty.get.state)}style="display:none"{/if}>
				{foreach from=$offers_state item=item key=key}
					<div class="checkbox">
						<label><input type="checkbox" name="state[]" value="{$item.name_url}" {if isset($smarty.get.state) && is_array($smarty.get.state) && $item.name_url|in_array:$smarty.get.state}checked{/if}>{$item.name}</label>
					</div>
				{/foreach}
			</div>
		</div>
	{/if}
	{if $settings.search_box_address}
		<div class="form-group">
			<input class="form-control btn-warning" type="submit" value="{'SEARCH!'|lang}">
		</div>
		<div class="form-group">
			<label for="search_box_address">{'Address'|lang}:</label>
			<input type="text" name="address" class="form-control" placeholder="{'1600 Pennsylvania Avenue NW, Washington, D.C. 20500'|lang}" title="{'Enter the address'|lang}" {if isset($smarty.get.address)}value="{$smarty.get.address}"{/if} id="search_box_address">
		</div>
		{if $settings.search_box_distance && $settings.google_maps_api}
			<div class="form-group">
				<div class="form-inline text-right">
					<div class="input-group">
						<div class="input-group-addon">{'Distance'|lang}: </div>
						<input type="number" name="distance" class="form-control text-right" placeholder="20" title="{'Enter the distance from the searched address'|lang}" value="{if isset($smarty.get.distance)}{$smarty.get.distance}{else}10{/if}" min="0" step="1" style="width:80px">
						<div class="input-group-addon">{'km'|lang}</div>
					</div>
				</div>
			</div>
		{/if}
	{/if}
	{if $settings.search_box_price}
		<div class="form-group">
			<label for="price" class="control-label">{'Price'|lang}: </label>
			<div class="input-group">
				<input class="form-control text-right" type="number" name="price_from" id="price_from" placeholder="{'Min...'|lang}" title="{'Enter the min value'|lang}" {if isset($smarty.get.price_from)}value="{$smarty.get.price_from}"{/if} min="0" step="0.01">
				<div class="input-group-addon"> - </div>
				<input class="form-control text-right" type="number" name="price_to" id="price_to" placeholder="{'Max...'|lang}" title="{'Enter the max value'|lang}" {if isset($smarty.get.price_to)}value="{$smarty.get.price_to}"{/if} min="0" step="0.01">
			</div>
		</div>
	{/if}
	{if isset($offers_options)}
		{foreach from=$offers_options item=item key=key}
			<div class="form-group {if !$item.type_all && isset($item.types)}offers_option_div {foreach from=$item.types item=item2 key=key2} offers_option_{$item2} {/foreach}{/if}">
				<label for="options[{$item.name}]" class="control-label">{$item.name}: </label>
				{if $item.kind=='text'}
					<input class="form-control" type="text" name="options[{$item.name_url}]" {if isset($smarty.get.options[$item.name_url])}value="{$smarty.get.options[$item.name_url]}"{/if}>
				{elseif $item.kind=='number'}
					<div class="input-group">
						<input class="form-control text-right" type="number" name="options[{$item.name_url}][from]" placeholder="{'Min...'|lang}" title="{'Enter the min value'|lang}" {if isset($smarty.get.options[{$item.name_url}].from)}value="{$smarty.get.options[{$item.name_url}].from}"{/if}>
						<div class="input-group-addon"> - </div>
						<input class="form-control text-right" type="number" name="options[{$item.name_url}][to]" placeholder="{'Max...'|lang}" title="{'Enter the max value'|lang}" {if isset($smarty.get.options[{$item.name_url}].to)}value="{$smarty.get.options[{$item.name_url}].to}"{/if}>
					</div>
				{elseif $item.kind=='select' && isset($item.choices)}
					<div class="group_checkbox">
						{foreach from=$item.choices item=item2 key=key2}
							<div class="checkbox">
								<label><input type="checkbox" name="options[{$item.name_url}][]" value="{$item2}" {if isset($smarty.get.options[{$item.name_url}]) && is_array($smarty.get.options[{$item.name_url}]) && $item2|in_array:$smarty.get.options[{$item.name_url}]}checked{/if}>{$item2}</label>
							</div>
						{/foreach}
					</div>
				{/if}
			</div>
		{/foreach}
	{/if}
	<div class="form-group">
		<input class="form-control btn-warning" type="submit" value="{'SEARCH!'|lang}">
	</div>
</form>

{if $settings.ads_side_2}<div class="ads">{$settings.ads_side_2}</div>{/if}