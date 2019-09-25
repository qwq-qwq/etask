{literal}
<script type="text/javascript">
	function test() {
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
	<input type="button" id="btnPerformedID" name="btnPerformed" {if $mode!='1' || $task.intState!='2'}style="font-size: 25px;" disabled{else}style="font-size: 25px; color: red;"{/if} onclick="test()" value="Выполнено" />
	<script>
	{if $done_not_allowed}$('#btnPerformedID').hide();{/if}
	</script>

	<table width="100%" cellpadding="0" cellspacing="0" class="header" style="text-align: center;" id="tasksTable">
	<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	<tr>
			<td width="200"><b>Номер заказа: </b></td>
			<td>{$task.intOrderID}</td>
		</tr>
		<tr>
			<td><b>Департамент-исполнитель</b></td>
			<td>{$addinfo.varExecutor}</td>
		</tr>
		<tr>
			<td><b>Департамент-отправитель</b></td>
			<td>{$addinfo.varSender}</td>
		</tr>
		<tr>
			<td><b>Адрес доставки</b></td>
			<td>{$addinfo.varAdress}</td>
		</tr>

	<tr>
		<td>Клиент</td>
		<td>
			{foreach from=$accounts item=item}
				{if $item.id eq $order.User_id} {$item.surname} {$item.name}{/if}
			{foreachelse}
				не зарегистрирован
			{/foreach}
		</td>
	</tr>
	<tr>
		<td>ФИО</td>
		<td>{$order.Contact_name}</td>
	</tr>
	<tr>
		<td>Телефон</td>
		<td>{$order.Contact_phone}</td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td>{$order.Contact_mail}</td>
	</tr>
	<tr>
		<td>Номер дисконтной карты</td>
		<td>{$order.bar_code}</td>
	</tr>
{if !empty($order.Organization_name)}
	<tr>
		<td colspan="2">Данные для печати на бухгалтерских документах</td>
	</tr>
	<tr>
		<td>Название организации</td>
		<td>{$order.Organization_name}</td>
	</tr>
	<tr>
		<td>Индивидуальный налоговый номер</td>
		<td>{$order.Tax_number}</td>
	</tr>
	<tr>
		<td>Номер свидетельства о регистрации плательщика налога на добавленную стоимость</td>
		<td>{$order.Vat_certificate}</td>
	</tr>
	<tr>
		<td>Адрес предприятия</td>
		<td>{$order.Org_address}</td>
	</tr>
{/if}


	<tr>
		<td>Состояние оплаты</td>
		<td>
			{foreach from=$paymentState item=item key=key}
				{if $order.is_preorder eq 1 && $key eq 1}

				{else}
					{if $key eq $order.Pay_state}{$item}{/if}
				{/if}
			{/foreach}
		</td>
	</tr>
	<tr>
		<td>Страна</td>
		<td>
			{foreach from=$countries item=item}
				{if $item.Country_id eq $order.Country_id}{$item.Name_RU}{/if}
			{/foreach}
		</td>
	</tr>
	<tr>
		<td>Город</td>
		<td>
			{foreach from=$cities item=item}
				{if $item.City_id eq $order.City_id}{$item.Name_RU}{/if}
			{/foreach}
		</td>
	</tr>
	<tr>
		<td>Тип доставки</td>
		<td>
			{foreach from=$deliverytypes item=item}
				{if $item.Delivery_type eq $order.Delivery_type}{$item.Name_RU}{/if}
			{/foreach}
		</td>
	</tr>
	<tr>
		<td>Дата доставки</td>
		<td>
		{$order.Delivery_date_from|date_format:'%d.%m.%Y'} c {$order.Delivery_date_from|date_format:'%H:%M'} по {$order.Delivery_date_to|date_format:'%H:%M'}
		</td>
	</tr>
	<tr>
		<td>Метод платежа</td>
		<td>
			{foreach from=$paymenttypes item=item}
				{if $item.Payment_type eq $order.Payment_type}{$item.Name_RU}{/if}
			{/foreach}
		</td>
	</tr>
	{if $order.Delivery_type == 3}
	<tr>
		<td>Магазин</td>
		<td>
			{foreach from=$gmaps item=item}
				{if $item.sprut_code eq $order.Shop_id}{$item.name_ru}{/if}
			{/foreach}
		</td>
	</tr>
	{/if}
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