{literal}
<script type="text/javascript">
	function test() {
		$('#event').val('doneTask');
		$('#orderForm').submit();
	}
	function getToExecute() {
		var process = true;
		{/literal}{if $not_payed_cashless}{literal}
		process = confirm('Внимание!!! Заказа не оплачен!!! Вы действительно хотите начать упаковку заказа?');
		{/literal}{/if}{literal}
		if (process) {
			$('#event').val('setExecutor');
			$('#orderForm').submit();
		}
	}
	$(document).ready(function () {
		$.post("ajax.php", { "event" : "GetPDFDocs",
							"intTaskType" : "{/literal}{$task.intType}{literal}",
							"intOrderID" : "{/literal}{$task.intOrderID}{literal}" },
			function(data){
				if (data) {
					var str = "<h3>Документы к задаче</h3>";
					var taskState = {/literal}{$task.intState}{literal};
					for(var i=0; i<data.length; i++) {
						if (taskState != 1 && taskState !=5) {
							str += '<a target="_blank" href="document.php?ord={/literal}{$task.intOrderID}{literal}&doc=' + data[i]['id'] + '&task={/literal}{$task.intID}{literal}">' + data[i]['name'] + '</a><br />';
						} else {
							str += data[i]['name'] + '<br />';
						}
					}
					$("#pdf_documents").html(str);
				}
			}, "json");
		{/literal}{if $mode=='2'}{literal}
		readonlyForm('orderForm');
		{/literal}{/if}{literal}
	});
</script>
{/literal}
<!--id="btnPerformed"-->
<div style="padding:20px;">
	<form name="orderForm" id="orderForm" method="post" enctype="multipart/form-data">
	{if $mode!='1' && $task.intState == '2'}<div style="color:red">Вы не можете обрабатывать эту задачу. Задачу выполняет {$exec_user}</div>{/if}
	<input type="button" name="btnSetExecutor" {if $mode!='1' || $task.intState!='1'}style="font-size: 25px;" disabled{else}style="font-size: 25px; color: red;"{/if} onclick="getToExecute()" value="Взять в работу" />
	<input type="button" name="btnPerformed" {if $mode!='1' || $task.intState!='2' || $not_payed_cashless}style="font-size: 25px;" disabled{else}style="font-size: 25px; color: red;"{/if} onclick="test()" value="Выполнено" />
	<div style="-moz-border-radius:3px 3px 3px 3px;
	background-color:red;
	color:white;
	text-align:center;
	display:inline;">
		{$card_note}
	</div>
	{*if $total_cost > 200}
	<div style="color: red; font-weight: bold; font-size: 16px;
	text-align:center; margin-top: 20px;">
		ВЛОЖИТЕ ПОДАРОК КЛИЕНТУ!
	</div>
	{/if*}
	{if $order.Pay_state}
	<div style="color: red; font-weight: bold; font-size: 16px; text-align:center; margin-top: 20px;">
		ЗАКАЗ УЖЕ ОПЛАЧЕН!!!
	</div>
	{/if}
	<table width="100%" cellpadding="0" cellspacing="0" class="header" style="text-align: center;" id="tasksTable">
	<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
	<tr>
			<th>№</th>
			<th>Название</th>
			<th>Артикул</th>
			<th>Необходимо упаковать</th>
		</tr>
	{foreach from=$goods item=item name=goods}
		<tr class="selected" style="{$item.style}">
			<td>{$smarty.foreach.goods.iteration}</td>
			<td style="text-align: left; padding-left:10px;">{$item.varArticleName}</td>
			<td width="20" nowrap>{$item.intArticleID}</td>
			<td class="demandQty" id="dem_Qty_{$smarty.foreach.goods.iteration}">{$item.intDemandQty}</td>
			<input type="hidden" name="dem_Qty_[{$item.intID}]" id="dem_Qty_[{$item.intID}]" value="{$item.intDemandQty}" />
		</tr>
	{/foreach}
	</table>
	<div id="pdf_documents"></div>
	<div style="clear:both;">
	{if $admin_comment_text|count_characters > 0}
		<div style="float:left;width:400px;">
			<h3>Комментарии менеджеров</h2>
			{$admin_comment_text|nl2br}
		</div>
	{/if}
	{if $user_comment_text|count_characters > 0}
		<div style="float:right;width:400px;">
			<h3>Комментарий клиента</h2>
			{$user_comment_text|nl2br}
		</div>
	{/if}
	</div>
	<br>
	{if $errors}
		<div style="padding: 10px; background: #F00">
			{$errors}
		</div>
	{/if}
	<input type="hidden" name="event" id="event" value="" />
	<input type="hidden" name="intTaskID" id="intTaskID" value="{$task.intID}" />
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td style="width:100%;">
			{include file="blocks/comments.tpl" pager=$comments script=1}
			</td>
		</tr>
	</table>
	</form>
</div>