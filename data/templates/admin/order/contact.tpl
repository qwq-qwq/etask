<div id="accountInfo">
<table id="clientInfo">
	<tr>
		<td align="right" width="250px;"><label for="User_id">Клиент</label></td>
		<td>
			<input type="text" name="user" id="User_Search"/>
			<select name="User_id" id="User_id">
				<option value="">не зарегистрирован</option>
			{foreach from=$accounts item=item}
				<option value="{$item.id}"{if $item.id eq $order.User_id} selected="selected"{/if}>{$item.surname} {$item.name} ({$item.login})</option>
			{/foreach}		
			</select> <input type="button" name="btnUpdateContactInfo" id="btnUpdateContactInfo" value="Обновить информацию">
		</td>
	</tr>	
	<tr>
		<td align="right"><label for="name">ФИО</label></td>
		<td><input type="text" name="Contact_name" id="name" value="{$order.Contact_name}" size="70" /></td>
	</tr>	
	<tr>
		<td align="right"><label for="phone">Телефон</label></td>
		<td><input type="text" name="Contact_phone" id="phone" value="{$order.Contact_phone}" size="70" /></td>
	</tr>	
	<tr>
		<td align="right"><label for="email">E-mail</label></td>
		<td><input type="text" name="Contact_mail" id="email" value="{$order.Contact_mail}" size="70" /></td>
	</tr>	
	<tr>
		<td align="right"><label for="delivery_address">Адрес доставки</label></td>
		<td><textarea name="Contact_address" id="delivery_address" cols="68" rows="5">{$order.Contact_address}</textarea></td>
	</tr>
	<tr>
		<td align="right"><label for="Barcode_pos">Номер дисконтной карты</label></td>
		<td><input type="text" name="Barcode_pos" id="bar_code" value="{$order.bar_code}" size="70" /></td>
	</tr>
	<tr>
		<td align="right"></td>
		<td><input type="checkbox" id="legalPerson"><label for="legalPerson">Юридическое лицо</label></td>
	</tr>
		
</table>

<table id="companyInfo">
	<tr>
		<td colspan="2">Данные для печати на бухгалтерских документах</td>
	</tr>	
	<tr>
		<td align="right" width="250px"><label for="organization_name">Название организации</label></td>
		<td><input type="text" name="Organization_name" id="organization_name" value="{$order.Organization_name|escape}" size="70" /></td>
	</tr>	 
	<tr>
		<td align="right"><label for="tax_number">Индивидуальный налоговый номер</label></td>
		<td><input type="text" name="Tax_number" id="tax_number" value="{$order.Tax_number}" size="70" /></td>
	</tr>	
	<tr>
		<td align="right"><label for="add_value_number">Номер свидетельства о регистрации плательщика налога на добавленную стоимость</label></td>
		<td><input type="text" name="Vat_certificate" id="add_value_number" value="{$order.Vat_certificate}" size="70" /></td>
	</tr>	
	<tr>
		<td align="right"><label for="organization_address">Адрес предприятия</label></td>
		<td><textarea name="Org_address" id="organization_address" cols="68" rows="5">{$order.Org_address}</textarea></td>
	</tr>
</table>	
</div>