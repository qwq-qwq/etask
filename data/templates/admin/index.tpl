<div align="center" valign="top" style="padding-top: 0px;">
{include file="blocks/scroller.tpl" pager=$tasks_list.pager prefix=tasks_list_ script=1}
<table class="header2">
	<tr>
	<th>{include file='blocks/sortlink.tpl' field='intID' text='№ задачи' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='intOrderID' text='№ заказа' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='intState' text='Статус' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='intDepartmentID' text='Департамент' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='intType' text='Тип задачи' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='varCreation' text='Дата/время создания' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='varStart' text='Дата/время начала' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='intExecutorID' text='Исполнитель' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='intCreatorID' text='Создатель' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>{include file='blocks/sortlink.tpl' field='varEnd' text='Дата/время окончания' sortorder=$sortOrder sortby=$sortBy script=true}</th>
	<th>До завершения</th>
	<th>КРI</th>
	<th>ФИО клиента</th>
	<th>Пользователь</th>
	</tr>
	{foreach from=$tasks_list item=item key=key}{if is_integer($key)}
	<tr ondblclick="go('task.php?ID={$item.intID}')" class="{if $item.is_preorder == 1}preorder_style_row{elseif $item.intState==1}greenrow{elseif $item.intState==2}bluerow{elseif $item.intState==4}redrow{elseif $item.intState==5}blackrow{elseif $item.intState==6}roserow{else}grayrow{/if} selected {$item.rowclass}">
		<td align="center">{$item.intID}</td>
		<td align="center" class="comment_popup">
			{$item.intOrderID}
			<div class="popup">
				<div class="popup_text left">
					{$item.ManagerComment|nl2br}
				</div>
				<div class="popup_text right">
					{$item.ClientComment|nl2br}
				</div>
			</div>
		</td>
		<td align="center" nowrap>{$item.varState}</td>
		<td>{$item.varDepartment}</td>
		<td>{$item.varType}</td>
		<td align="center">{$item.varCreation}</td>
		<td align="center">{$item.varStart}</td>
		<td>{$item.varExecutor}</td>
		<td>{$item.varCreator}</td>
		<td align="center">{$item.varEnd}</td>
		<td>{$item.varTimeLeft}</td>
		<td>{if in_array($item.intState, array(3,4,6))}{$item.intKPI}%{/if}</td>
		<td>{$item.varFIO}</td>
		<td>{$item.varUserName}</td>
	</tr>
	{/if}{/foreach}
</table>
{include file="blocks/scroller.tpl" pager=$tasks_list.pager prefix=tasks_list_ script=1}
</div>