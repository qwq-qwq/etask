{if count($messages) }
<center>
	<div>
		{foreach from=$messages item=msg key=key}
			{if $msg.isError == 1}
				<div style="font-size: 12px; color: red;">
					{$msg.text}<br/>
				</div>
			{/if}
			{if $msg.isError == 0}
				<div style="font-size: 12px; color: green;">
					{$msg.text}<br/>
				</div>
			{/if}
		{/foreach}
	</div>
</center>
{/if}
