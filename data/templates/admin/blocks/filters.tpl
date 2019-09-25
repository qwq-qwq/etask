<form name="taskslist" id="taskslist" method="post" action="index.php#results">
	<input type="hidden" name="event" id="event" value="search">
	<input type="hidden" name="sortOrder" id="sortOrder" value="{$sortOrder}"/>
	<input type="hidden" name="sortBy" id="sortBy" value="{$sortBy}"/>
	<input type="hidden" name="varDepartmentID" id="varDepartmentID" value=""/>
	<input type="hidden" name="menuBlock" id="menuBlock" value="{$menuBlock}"/>
	<input type="hidden" name="menudep" id="menudep" value="{$menudep}"/>
	<input type="hidden" name="menumydep" id="menumydep" value="{$menumydep}"/>
	<input type="hidden" name="menumy" id="menumy" value="{$menumy}"/>
	<input type="hidden" name="tasks_page" id="tasks_page" value="" />
<table id="filters">
	<tr>
		<td align="right">Поиск по номеру артиклу товара:</td>
		<td colspan="2"><input type="text" name="Warez_id" value="{$filter.Warez_id}"></td>
	</tr>
	<tr>
		<td align="right">Поиск по номеру задачи:</td>
		<td colspan="2"><input type="text" name="intID" value="{$filter.intID}"></td>
	</tr>
	<tr>
		<td align="right">Поиск по номеру заказа bukva.ua:</td>
		<td colspan="2"><input type="text" name="intOrderID" value="{$filter.intOrderID}"></td>
	</tr>
	<tr>
		<td align="right">Поиск по фамилии заказчика:</td>
		<td colspan="2"><input type="text" name="Contact_name" value="{$filter.Contact_name}"></td>
	</tr>
	<tr>
		<td align="right">Поиск по телефону заказчика:</td>
		<td colspan="2"><input type="text" id="Contact_phone" name="Contact_phone" value="{$filter.Contact_phone}"></td>
	</tr>
	<tr>
		<td align="right">Тип задачи</td>
		<td colspan="2">
			<select name="intType" id="intType">
				<option value="">Все</option>
				{foreach from=$task_types_list item=item}
				<option value="{$item.intID}"{if $filter.intType eq $item.intID} selected="selected"{/if}>{$item.varName}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Департамент</td>
		<td colspan="2">
			<select name="intDepartmentID[]" id="intDepartmentID" multiple="multiple" size="7" onchange="$('#menuBlock').val('');">
				{foreach from=$departments_list item=item}
					{assign var='dselected' value='0'}
					{foreach from=$departmentsSelected item=ditem}
						{if $item.intVarID eq $ditem.intVarID}
							{assign var='dselected' value='1'}
						{/if}
					{/foreach}
				<option value="{$item.intVarID}"{if $dselected eq '1'} selected="selected"{/if}>{$item.varValue}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Статус</td>
		<td colspan="2">
			<select name="intState" id="intState">
				<option value="">Все</option>
				{foreach from=$task_state_list item=item}
				<option value="{$item.intID}"{if $filter.intState eq $item.intID} selected="selected"{/if}>{$item.varName}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Автор</td>
		<td colspan="2">
			<select name="intCreatorID" id="intCreatorID">
				<option value="">Все</option>
				{foreach from=$users_list item=item}
				<option value="{$item.intUserID}"{if $filter.intCreatorID eq $item.intUserID} selected="selected"{/if}>{$item.varFIO}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Исполнитель</td>
		<td colspan="2">
			<select name="intExecutorID" id="intExecutorID">
				<option value="">Все</option>
				{foreach from=$users_list item=item}
				<option value="{$item.intUserID}"{if $filter.intExecutorID eq $item.intUserID} selected="selected"{/if}>{$item.varFIO}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td align="right"><label for="is_preorder">Тип заказа</label></td>
		<td colspan="2">
			<select id="is_preorder" name="is_preorder">
				<option value=''>Заказы + Предзаказы</option>
				<option value='1' {if $filter.is_preorder=='1'}selected{/if}>Только предзаказы</option>
				<option value='0' {if $filter.is_preorder=='0'}selected{/if}>Только заказы</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Дата создания с</td>
		<td><input type="text" name="varCreationFrom" id="varCreationFrom" value="{$filter.varCreationFrom}" size="15"></td>
		<td>по <input type="text" name="varCreationTo" id="varCreationTo" value="{$filter.varCreationTo}" size="15"></td>
	</tr>
	<tr>
		<td align="right">Дата завершения с</td>
		<td><input type="text" name="varEndFrom" id="varEndFrom" value="{$filter.varEndFrom}" size="15"></td>
		<td>по <input type="text" name="varEndTo" id="varEndTo" value="{$filter.varEndTo}" size="15"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" align="center">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="padding: 5px 10px 5px 0">
						<input id="cb_show_5" type="checkbox" name="show_5" {if $filter.NOTINintState.5 != 5}checked{/if} value="1">
						<label for="cb_show_5">Показывать заблокированные</label>
					</td>
					<td>
						<input id="cb_show_3" type="checkbox" name="show_3" {if $filter.NOTINintState.3 != 3}checked{/if} value="1">
						<label for="cb_show_3">Показывать выполненные</label>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 10px 0 0">
						<input id="cb_show_4" type="checkbox" name="show_4" {if $filter.NOTINintState.4 != 4}checked{/if} value="1">
						<label for="cb_show_4">Показывать отказы</label>
					</td>
					<td>
						<input id="cb_show_6" type="checkbox" name="show_6" {if $filter.NOTINintState.6 != 6}checked{/if} value="1">
						<label for="cb_show_6">Показывать выполненные частично</label>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2">
			<input type="submit" value="Применить фильтр" style="margin-left:10px;">
			<input style="margin-left:66px;" type="button" value="Снять все фильтры" onclick="{if $mode eq '1'}setfilter('dep','','{$useralldepartments}',''){else}setfilter('mydep','','{$userdepartments}',''){/if};">
		</td>
	</tr>
</table>

</form>
