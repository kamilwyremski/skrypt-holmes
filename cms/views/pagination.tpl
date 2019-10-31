
{if $pagination.page_count>1}
	<div id="select_page">
		<p>{'Page'|lang}: {$pagination.page_number} / {$pagination.page_count}</p>
		<a href="?{$pagination.page_url.page_cms}" title="{'First page'|lang}" class="link_page link_page_first {if $pagination.page_number==1}inactive{/if}"></a>
		<a href="?{$pagination.page_url.page_cms}&page={$pagination.page_number-1}" title="{'Previous page'|lang}" class="link_page link_page_lewo {if $pagination.page_number==1}inactive{/if}"></a>	
		<a href="?{$pagination.page_url.page_cms}&page={$pagination.page_number+1}" title="{'Next page'|lang}" class="link_page link_page_prawo {if $pagination.page_number==$pagination.page_count}inactive{/if}"></a>
		<a href="?{$pagination.page_url.page_cms}&page={$pagination.page_count}" title="{'Last page'|lang}" class="link_page link_page_last {if $pagination.page_number==$pagination.page_count}inactive{/if}"></a>
	 </div>
{/if}

	

