{if $script}
<a href="javascript:sortByField('{$field}','{if $sortby==$field}{if $sortorder}0{else}1{/if}{else}0{/if}'{if $prefix},'{$prefix}'{/if});">{$text}</a>&nbsp;{if $sortby==$field}{if !$sortorder}&uarr;{else}&darr;{/if}{/if}
{else}
<a href="?{$prefix}sortBy={$field}&{$prefix}sortOrder={$sortorder}">{$text}</a>&nbsp;{if $sortby==$field}{if $sortorder}&uarr;{else}&darr;{/if}{/if}
{/if}