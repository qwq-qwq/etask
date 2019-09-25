{literal}
<script type="text/javascript">
	function r2(n) {
	  ans = n * 1000
	  ans = Math.round(ans /10) + ""
	  while (ans.length < 3) {ans = "0" + ans}
	  len = ans.length
	  ans = ans.substring(0,len-2) + "." + ans.substring(len-2,len)
	  return ans
	}

	function test() {
		var toManyFlag = false;
		var toShortFlag = false;
		var numFlag = false;
		var msgText = '';

		$('#orderForm input[type=text]').each(function(index, item) {
			if(!numFlag) {
				if(isNaN(Number($(item).val())) || $(item).val() == null) {
					numFlag = true;
				}
			}
		});
		if(numFlag) { alert('В полях должны быть только числа!'); return; }

		$('#orderForm input[type=text]').each(function(index, item) {
			if(!toManyFlag) {
				var i = index + 1;
				if(Number($(item).val()) > Number($('#dem_Qty_' + i).text())) {
					toManyFlag = true;
				}
			}
		});
		if(toManyFlag) { alert('Ошибка! Собрано слишком много!'); return; }

		$('#orderForm input[type=text]').each(function(index, item) {
			//if(!toShortFlag) {
				var i = index + 1;
				if(Number($(item).val()) < Number($('#dem_Qty_' + i).text())) {
					var pp = Number($('#dem_Qty_' + i).text()) - Number($(item).val());
					var ppp = r2($('#dem_Qty_' + i).attr('price')*pp);
					msgText	= msgText + "\n" + i + ". " + $('#dem_Qty_' + i).attr('articul') + " - " + $('#dem_Qty_' + i).attr('rel') + " - " + pp +"шт., на сумму " + ppp + "грн";
					toShortFlag = true;
				}
			//}
		});
		if(toShortFlag) {
			if(!confirm('Собрано товаров меньше чем необходимо?')) {
				return;
			}
			if(confirm(msgText + "\n\n\n" + 'Перевести недостающие товары на склад недостач по приемке товаров?')) {
				$('#nedostach').val(1);

			}
		}
		$('#event').val('doneTask');
		$('#orderForm').submit();
	}
	function getToExecute() {
		$('#event').val('setExecutor');
		$('#orderForm').submit();
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
<div style=" padding:20px;">
	<form name="orderForm" id="orderForm" method="post" enctype="multipart/form-data">
	{if $mode!='1' && $task.intState == '2'}<div style="color:red">Вы не можете обрабатывать эту задачу. Задачу выполняет {$exec_user}</div>{/if}
	<input type="button" name="btnSetExecutor" {if $mode!='1' || $task.intState!='1'}style="font-size: 25px;" disabled{else}style="font-size: 25px; color: red;"{/if} onclick="getToExecute()" value="Взять в работу" />
	<input type="button" name="btnPerformed" {if $mode!='1' || $task.intState!='2'}style="font-size: 25px;" disabled{else}style="font-size: 25px; color: red;"{/if} onclick="test()" value="Выполнено" />
	<input type="hidden" name="nedostach" id="nedostach" value="0">

	<table width="100%" cellpadding="0" cellspacing="0" class="header" style="text-align: center;" id="tasksTable">
		<tr>
			<td colspan="5" align="left"><h3>Товары</h3></td>
		</tr>
		<tr>
			<th>№</th>
			<th>Название</th>
			<th>Артикул</th>
			<th>Необходимо собрать</th>
			<th>Собрано</th>
		</tr>
	{foreach from=$goods item=item name=goods}
		<tr class="selected" style="{$item.style}">
			<td>{$smarty.foreach.goods.iteration}</td>
			<td style="text-align: left; padding-left:10px;">{$item.varArticleName}</td>
			<td width="20" nowrap>{$item.intArticleID}<input type="hidden" name="code_wares[{$item.intID}]" value="{$item.intArticleID}"></td>
			<td class="demandQty" id="dem_Qty_{$smarty.foreach.goods.iteration}" articul="{$item.intArticleID}" price="{$item.Price}" rel="{$item.varArticleName|escape}">{$item.intDemandQty}</td>
			<input type="hidden" name="dem_Qty_[{$item.intID}]" id="dem_Qty_[{$item.intID}]" value="{$item.intDemandQty}" />
			<td><input type="text" name="Qty[{$item.intID}]" id="Qty_{$smarty.foreach.goods.iteration}" value="{$item.intDoneQty}" size="3" /></td>
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