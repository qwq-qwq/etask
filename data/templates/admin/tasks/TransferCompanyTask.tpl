{literal}
<script type="text/javascript">
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
<style type="text/css">
	#tasksTable td {
		text-align: left;
		line-height: 20px;
	}
</style>
{/literal}
<!--id="btnPerformed"-->
<div style="padding:20px;">
	<form name="orderForm" id="orderForm" method="post" enctype="multipart/form-data">
	{if $mode!='1' && $task.intState == '2'}<div style="color:red">Вы не можете обрабатывать эту задачу. Задачу выполняет {$exec_user}</div>{/if}
	<input type="button" name="btnSetExecutor" {if $mode!='1' || $task.intState!='1'}style="font-size: 25px;" disabled{else}style="font-size: 25px; color: red;"{/if} onclick="getToExecute()" value="Взять в работу" />

	<table width="100%" cellpadding="0" cellspacing="0" class="header" style="text-align: center;" id="tasksTable">
		<tr>
			<td colspan="2" style="text-align: right;">
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><b>Номер заказа: </b></td>
			<td>{$invoice.intOrderID}</td>
		</tr>
		<tr>
			<td><b>Номер накладной на перемещение: </b></td>
			<td>{$invoice.intNumberInvoice}</td>
		</tr>
		<tr>
			<td><b>Департамент-отправитель</b></td>
			<td>{$invoice.shopFromName}</td>
		</tr>
		<tr>
			<td><b>Департамент-получатель</b></td>
			<td>{$invoice.shopToName}</td>
		</tr>
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