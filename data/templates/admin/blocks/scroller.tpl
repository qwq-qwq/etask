{if $script}<div class="pager"><input type="hidden" name="{$prefix}page" id="{$prefix}page" value="{$pager.current_page}" />
	{if $pager.current_page>1}<a href="javascript:loadPage({$pager.current_page-1},'{$prefix}')">←</a>{/if}
	{foreach from=$pager.page item=page}
	<a href="javascript:loadPage({$page},'{$prefix}')" {if $pager.current_page==$page} style="color:#666;"{/if}>{$page}</a>
	{/foreach}
	{if $pager.current_page<$pager.last_page}<a href="javascript:loadPage({$pager.current_page+1},'{$prefix}')">→</a>{/if}</div>
{else}<div class="pager">
	{if $pager.current_page>1}<a href="{$smarty.server.SCRIPT_NAME}?{$prefix}page={$pager.current_page-1}{$query|default:''}">←</a>{/if}
	{foreach from=$pager.page item=page}
	<a href="{$smarty.server.SCRIPT_NAME}?{$prefix}page={$page}{$query|default:''}" {if $pager.current_page==$page} class="selected"{/if}>{$page}</a>
	{/foreach}
	{if $pager.current_page<$pager.last_page}<a href="{$smarty.server.SCRIPT_NAME}?{$prefix}page={$pager.current_page+1}{$query|default:''}">→</a>{/if}</div>
{/if}