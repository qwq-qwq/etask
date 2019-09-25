<div style="-moz-border-radius:3px 3px 3px 3px;
background-color:red;
display: block;
color:black;
text-align:center;float: left">
	{$card_note}
	{if $offer_vip}
		<input type="button" id="reject_vip_button" onclick="RejectVIPCard()" value="Клиент отказывается">
	{/if}
</div>
<br clear="all"/>
<input type="button" id="btnCancelOrder" value="Отменить заказ" style="float:right;" />

<div style="display:inline;float:right;margin:0 10px;">
	Магазин сбора заказа:
	<select name="asm_shop_id"{if !$choose_asm_shop} disabled="disabled"{/if}>
		<option value="0">Выбрать автоматически</option>
	{foreach from=$asm_shops item=shop}
		<option value="{$shop.intCodeShopSprut}"{if $shop.intCodeShopSprut == $order.Asm_shop_id && $show_shop} selected="selected"{/if}>{$shop.varValue}</option>
	{/foreach}
	</select>
</div>
<div style="display:inline;float:right;margin:0 10px;"><label for="codeArticle">Код</label> <input type="text" name="codeArticle" id="codeArticle" size="10" /></div>
<table width="100%" class="header padding2" cellpadding="1" cellspacing="1">
	<tr>
		<td colspan="2"><h3>Товары</h3></td>
		<td colspan="3"><input type="button" id="btnPreorder" value="{if $order.is_preorder eq 1}Предзаказ -> Заказ{else}Заказ -> Предзаказ{/if}" /></td>
		<td colspan="5" align="right" id="addProduct_block" nowrap>
			<label for="productArticle">Артикул</label> <input type="text" name="productArticle" id="productArticle" size="10" />
			<label for="productAmount">Кол-во</label> <input type="text" name="productAmount" id="productAmount" size="3" value="1" />
			<input type="button" id="btnAddProduct" value="Добавить товар" />
		</td>
	</tr>
	<tr>
		<th>№</th>
		<th>Название</th>
		<th>Издательство</th>
		<th>Артикул</th>
		<th>Цена</th>
		<th>Скидка</th>
		<th>Цена со скидкой</th>
		<th>Кол-во</th>
		<th>Сумма</th>
		<!--<th>Обещали клиенту</th>-->
		<th></th>
	</tr>
{assign var="totalSum" value="0"}
{assign var="bgcol" value="white"}
{foreach from=$sales item=item name=sales}
{if $bgcol!='#FAFFB2'}{assign var="bgcol" value="#FAFFB2"}{else}{assign var="bgcol" value="white"}{/if}
	<tr class="selected" style="background-color:{$bgcol}; {$item.style}" ondblclick="gopopup('{$shop_url}catalog/browse/{$item.Group_id}/1/{$item.Wares_id}')">
		<td>{$smarty.foreach.sales.iteration}</td>
		<td>{$item.Name}</td>
		<td>{$item.Brand}</td>
		<td>{$item.Wares_id}</td>
		<td>{math equation="x" x=$item.Price format="%.2f"}</td>
		<td>{$item.discount}%</td>
		<td id="priceDiscount{$smarty.foreach.sales.iteration}">{math equation="x" x=$item.PriceDiscount format="%.2f"}</td>
		<td><input type="text" name="Qty[{$item.Wares_id}]" id="Qty_{$item.Wares_id}" value="{$item.Qty}" size="3"  class="qty" count="{$smarty.foreach.sales.iteration}" /></td>
		<td id="sum{$smarty.foreach.sales.iteration}">{math equation="x" x=$item.Sum format="%.2f"}</td>
		<!--<td style="color:{$item.Est_deliv_color};">{$item.Est_deliv_name}</td>-->
		<td align="right"><a class="deleteProduct" href="#" waresID="{$item.Wares_id}" waresName="{$item.Name}" title="Удалить товар: {$item.Name}"><img src="img/delete-icon.png" alt="Удалить"></a></td>
	</tr>
	{if $show_remains}
		{foreach from=$remains item=rem}
			{if $rem.Wares_id == $item.Wares_id}
				<tr style="background-color:{$bgcol}; color:{$rem.Color};" class="remainsrow">
					<td colspan="2">{$rem.Warehouse_name}</td>
					<td colspan="6">{$rem.Region}</td>
					<td>{$rem.Qty} шт.</td>
				</tr>
			{/if}
		{/foreach}
	{/if}
{assign var="totalSum" value="`$totalSum+$item.Sum`"}
{/foreach}
	<tr class="selected" id="DeliveryPrice_row">
		<td>{$smarty.foreach.sales.total+1}</td>
		<td colspan="2">Доставка {if $order.Delivery_type == 1}курьером{elseif $order.Delivery_type == 2}автолюксом{elseif $order.Delivery_type == 3}самовывоза{elseif $order.Delivery_type == 4}Укрпочтой{/if}</td>
		<td colspan="2"><input type="text" name="DeliveryPrice" id="DeliveryPrice" value="{$order.Overcost}" size="5" /></td>
		<td colspan="2">&nbsp;</td>
		<td id="DeliveryPriceSum">{$order.Overcost}</td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<th>Итого</th>
		<th nowrap><span id="totalPrice">{math equation="x" x=$order.Cost+$order.Overcost format="%.2f"}</span> грн.</th>
		<td colspan="2"></td>
	</tr>
</table>
<br /><br />
<table width="100%">
	<tr style="font-size: 14px;">
		<td width="447"  style="font-size: 14px;"><b>Тип доставки: </b>
			{foreach from=$deliverytypes item=item}
				{if $item.Delivery_type==$order.Delivery_type}{$item.Name_RU}{/if}
			{/foreach}
		</td>
		<td style="font-size: 14px;"><b>Служба доставки: </b>
		{if $order.Delivery_type==1}
			{if $task.intDeliveryService == 20}Доставка Bukva{else}XPOST{/if}
		{/if}
		</td>
	</tr>
</table>
<br />
<div id="pdf_documents" style="display: none;"></div>
<table id="orderComments">
	<tr>
		<td>
			<label for="Adm_comment">Комментарии менеджеров</label><br />
			<textarea name="Adm_comment" id="Adm_comment" cols="68" rows="6">{$order.Adm_comment}</textarea><br/>
			<label for="adminComment">Введите комментарий</label><br/>
			<textarea name="adminComment" id="adminComment" cols="68" rows="1"></textarea><br />
			<input type="button" value="Добавить комментарий" name="btnAddAdminComment" id="btnAddAdminComment" />
		</td>
		<td valign="top">
			<label for="Ord_comment">Комментарий заказчика</label><br />
			<textarea name="Ord_comment" id="Ord_comment" cols="68" rows="6">{$order.Ord_comment}</textarea><br />
		</td>
	</tr>
	<tr>
		<td colspan="2">{include file="blocks/comments.tpl" pager=$comments script=1}</td>
	</tr>
</table>

