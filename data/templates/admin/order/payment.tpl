<table id="paymenyInfo">
	<tr>
		<td align="right"><label for="Pay_state">Состояние оплаты</label></td>
		<td>
			<select name="Pay_state" id="Pay_state">
			{foreach from=$paymentState item=item key=key}
				{if $order.is_preorder eq 1 && $key eq 1}

				{else}
				<option value="{$key}"{if $key eq $order.Pay_state} selected="selected"{/if}>{$item}</option>
				{/if}
			{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right"><label for="Country_id">Страна</label></td>
		<td>
			{if $task.intType == 20}
			<input type="hidden" name="Country_id" value="{$order.Country_id}">
			<select disabled>
			{else}
			<select name="Country_id" id="Country_id">
			{/if}
			{assign var="region" value="0"}
			{foreach from=$countries item=item}
				{if $region != $item.Region_id}<optgroup label="{$item.Region_name}">{/if}
				<option value="{$item.Country_id}"{if $item.Country_id eq $order.Country_id} selected="selected"{/if}>{$item.Name_RU}</option>
				{assign var="region" value=$item.Region_id}
				{if $region != $item.Region_id}</optgroup>{/if}
			{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right"><label for="City_id">Город</label></td>
		<td>
			{if $task.intType == 20}
			<input type="hidden" name="City_id" value="{$order.City_id}">
			<select disabled>
			{else}
			<select name="City_id" id="City_id">
			{/if}
			{foreach from=$cities item=item}
				<option value="{$item.City_id}"{if $item.City_id eq $order.City_id} selected="selected"{/if}>{$item.Name_RU}</option>
			{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" width="250px"><label for="Delivery_type">Тип доставки</label></td>
		<td>
			{if $task.intType == 20}
			<input type="hidden" name="Delivery_type" value="{$order.Delivery_type}">
			<select disabled>
			{else}
			<select name="Delivery_type" id="Delivery_type">
			{/if}
			{foreach from=$deliverytypes item=item}
				<option value="{$item.Delivery_type}"{if $item.Delivery_type eq $order.Delivery_type} selected="selected"{/if}>{$item.Name_RU}</option>
			{/foreach}
			</select>
		</td>
	</tr>
    <tr id="Delivery_service_row" {if $order.Delivery_type!=1}style="display: none;"{/if}>
		<td align="right" width="250px"><label for="intDeliveryService">Служба доставки</label></td>
		<td>
			{if $task.intType == 20}
			<input type="hidden" name="intDeliveryService" value="{$task.intDeliveryService}">
			<select disabled>
			{else}
			<select name="intDeliveryService" id="intDeliveryService">
			{/if}
			{foreach from=$deliveryservices item=item key=key}
				<option value="{$key}"{if $key eq $task.intDeliveryService} selected="selected"{/if}>{$item}</option>
			{/foreach}
			</select>
		</td>
	</tr>
	<tr id="Delivery_whs" {if $order.Delivery_type!=5}style="display: none;"{/if}>
		<td align="right" width="250px"><label for="intwhs">Склад</label></td>
		<td>
			<select name="intWHS" id="intWHS">
			{foreach from=$whs item=item key=key}
				<option rel="{$item.city_id}" {if $order.City_id!=$item.city_id}style="display: none;"{/if} value="{$item.id}"{if $order.Shop_id==$item.id} selected="selected"{/if} res="{$item.open_ru|escape}">{$item.name_ru|escape}</option>
			{/foreach}
			</select>
		</td>
	</tr>
	<tr id="Delivery_date_row">
		<td align="right"><label for="Delivery_date">Дата доставки</label></td>
		<td>
		<input type="text" name="Delivery_date" id="Delivery_date" value="{if !empty($order.Delivery_date_from)}{$order.Delivery_date_from|date_format:'%d.%m.%Y'}{/if}" size="10" />
		&nbsp;
		с&nbsp;
		<select name="Delivery_date_from_hour" id="Delivery_date_from_hour">
		<option value="8">08</option>
		<option value="9">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		</select>
		<select name="Delivery_date_from_minutes" id="Delivery_date_from_minutes">
		<option value="0">00</option>
		<option value="10">10</option>
		<option value="20">20</option>
		<option value="30">30</option>
		<option value="40">40</option>
		<option value="50">50</option>
		</select>
		&nbsp;по&nbsp;
		<select name="Delivery_date_to_hour" id="Delivery_date_to_hour">
		<option value="8">08</option>
		<option value="9">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		</select>
		<select name="Delivery_date_to_minutes" id="Delivery_date_to_minutes">
		<option value="0">00</option>
		<option value="10">10</option>
		<option value="20">20</option>
		<option value="30">30</option>
		<option value="40">40</option>
		<option value="50">50</option>
		</select>
		<script>
		$('#Delivery_date_from_hour').val({$order.Delivery_date_from|date_format:'%H'});
		$('#Delivery_date_from_minutes').val({$order.Delivery_date_from|date_format:'%M'});
		$('#Delivery_date_to_hour').val({$order.Delivery_date_to|date_format:'%H'});
		$('#Delivery_date_to_minutes').val({$order.Delivery_date_to|date_format:'%M'});
		{literal}
		$(function(){
		$('#Delivery_type').change(function(){
			if($(this).val()==1){
				$('#Delivery_service_row').show();
                $('#Delivery_whs').hide();
			} else if($(this).val()==5) {
                $('#Delivery_service_row').hide();
				$('#Delivery_whs').show();
			} else {
				$('#Delivery_service_row').hide();
                $('#Delivery_whs').hide();
			}
		})
        $('#City_id').change(function(){
            var cit = $(this).val();
			$('#Delivery_whs option').each(function(){
                if($(this).attr('rel')!=cit) {
                    $(this).css('display','none');
                } else {
                    $(this).css('display','block');
                }
            })
		})
        $('#Delivery_type').change(function(){
        	if($(this).val()=='5'){
        		$('#intWHS').change();
        	} else if($(this).val()=='3') {
        		$('#Shop_id').change();
        	}
        })
        $('#intWHS').change(function(){
        	$('#delivery_address').val($(this).find(':selected').text()+"\n"+$(this).find(':selected').attr('res'));
        })
        $('#Shop_id').change(function(){
        	$('#delivery_address').val($(this).find(':selected').attr('rel'));
        })
        })
		{/literal}
		</script>

		</td>
	</tr>
	<!--<tr id="Courier_id_row">
		<td align="right"><label for="Courier_id">Курьер</label></td>
		<td>
			<select name="Courier_id" id="Courier_id">
			{foreach from=$couriers item=item}
				<option value="{$item.id}"{if $item.id eq $order.Courier_id} selected="selected"{/if}>{$item.surname} {$item.name}</option>
			{/foreach}
			</select>
		</td>
	</tr>-->
	<tr>
		<td align="right"><label for="Payment_type">Метод платежа</label></td>
		<td>
			<select name="Payment_type" id="Payment_type">
			{foreach from=$paymenttypes item=item}
				<option value="{$item.Payment_type}"{if $item.Payment_type eq $order.Payment_type} selected="selected"{/if}>{$item.Name_RU}</option>
			{/foreach}
			</select>
		</td>
	</tr>
	<tr id="Shop_id_row">
		<td align="right"><label for="Shop_id">Магазин</label></td>
		<td>
			{if $task.intType == 20}
			<input type="hidden" name="Shop_id" value="{$order.Shop_id}">
			<select disabled>
			{else}
			<select name="Shop_id" id="Shop_id">
			{/if}
			{foreach from=$gmaps item=item}
				<option value="{$item.sprut_code}"{if $item.sprut_code eq $order.Shop_id} selected="selected"{/if} rel="{$item.description_ru|escape}">{$item.name_ru}</option>
			{/foreach}
			</select>
		</td>
	</tr>
</table>