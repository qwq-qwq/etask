<script language="javascript">
{if $mode eq '2' || $intUserID != $task.intExecutorID}
{literal}
$(document).ready(function(){
	readonlyForm('orderForm');
	{/literal}
	{if $task.intState == 1}
	{literal}
		$('#btnSetExecutor').removeAttr('disabled');
	{/literal}
	{/if}
	{literal}
});
{/literal}
{else}
{literal}
$(document).ready(function(){
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
				$("#pdf_documents").html(str).show();
			}
		}, "json");
	var notDisabled = new Array("btnGraph", "btnCancelOrder", "btnAddComment","btnShowComment","btnAddAdminComment","btnAddProduct","btnPreorder",'btnRemainsOrder');
	disableButton('orderForm', notDisabled);
	{/literal}
	{if $graph_built}
		{if $done_allowed}
			{literal}
				$('#btnPerformed').removeAttr('disabled');
			{/literal}
		{/if}
		{literal}
			var GraphWin = window.open('graph.html', 'Graph', "width=1200,height=740,left=10,top=10,menubar=no,toolbar=no,location=no,directories=no,status=no,resizable=no,scrollbars=yes");
			if (GraphWin !== null) {
				GraphWin.Graph_array = eval('{/literal}{$GRAPH}{literal}');
				GraphWin.focus();
			}
		{/literal}
	{/if}
{literal}
});
{/literal}
{/if}
</script>

<form name="orderForm" id="orderForm" method="post" enctype="multipart/form-data">
<input type="hidden" name="event" id="event" value="" /> <!-- Action name -->
<input type="hidden" name="ID" id="ID" value="{$task.intID}" /> <!-- Task ID -->
<input type="hidden" name="downloadDocumentID" value="" />
<input type="hidden" name="Wares_id" id="Wares_id" value="" />
<input type="hidden" name="User_name" id="User_name" value="{$userName}" />
<input type="hidden" name="is_preorder" id="is_preorder" value="{$order.is_preorder}" />
<!--Graph related data-->
<input type="hidden" name="serialized_graph" id="hiSerializedGraph" value="{$serialized_graph}" />
{if $mode!='1' && $task.intState == '2'}
<div style="color:red">
Вы не можете обрабатывать эту задачу. Задачу выполняет {$exec_user}
<a style="color:#00AA00" href="javascript:$.noop()" onclick="$('#event').val('UnlockTask');$('#orderForm').submit();">Разблокировать</a>
</div>
{/if}
{if $codeNotValid}
<div style="color:red">
Код на скидку не применен
</div>
{/if}
<div id="tabs">
    <ul>
        <li><a href="#fragment-1"><span>Данные заказа</span></a></li>
        <li><a href="#fragment-2"><span>Оплата и доставка</span></a></li>
        <li><a href="#fragment-3"><span>Контактные данные</span></a></li>
        <li style="width:556px; text-align:right;">
        	<input type="button" name="btnPerformed" id="btnPerformed" value="Выполнено" />
        	<input type="button" name="btnGraph" id="btnGraph" value="Построить граф" />
        	<input type="button" name="btnRemainsOrder" id="btnRemainsOrder" value="Посмотреть остатки" />
        	<input type="button" name="btnSetExecutor" id="btnSetExecutor" value="Взять на исполнение" />
        	<input type="button" name="saveOrder" id="saveOrder" value="Сохранить" />
        </li>
    </ul>
    <div id="fragment-1">{include file="order/sales.tpl"}</div>
    <div id="fragment-2">{include file="order/payment.tpl"}</div>
    <div id="fragment-3">{include file="order/contact.tpl"}</div>
</div>

</form>