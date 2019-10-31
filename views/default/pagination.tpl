
{if $pagination.page_count}
	<div class="text-center">
		<ul class="pagination">
			<li {if $pagination.page_number==1}class="disabled return_false"{/if}><a href="{$links[$page]}{if $pagination.page_url.page}?{/if}{$pagination.page_url.page}" title="{'First page'|lang}">&laquo;</a></li>
			{for $this_page=$pagination.page_start to $pagination.page_count max=9}
				<li {if $pagination.page_number==$this_page}class="disabled return_false active"{/if}><a href="{$links[$page]}?{$pagination.page_url.page}{if $pagination.page_url.page}&{/if}page={$this_page}" title="{'Page'|lang}: {$this_page}">{$this_page}</a></li>
			{/for}
		   <li {if $pagination.page_number==$pagination.page_count}class="disabled return_false"{/if}><a href="{$links[$page]}?{$pagination.page_url.page}{if $pagination.page_url.page}&{/if}page={$pagination.page_count}" title="{'Last page'|lang}">&raquo;</a></li>
		</ul>
	</div>
{/if}