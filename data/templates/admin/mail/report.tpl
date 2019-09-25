<table border="1" style="font-size:12px;text-align:center;">
	<tr style="font-weight:bold;text-align:center;">
		<td>Номер заказа</td>
		<td>Статус</td>
		<td>На сумму</td>
		<td>Доставка с</td>
		<td>Доставить по</td>
		<td>Магазин сбора</td>
		<td>Адрес магазина</td>
		<td>Адрес доставки</td>
		<td>ФИО</td>
		<td>Телефон</td>
		<td>Заказа создан</td>
		<td>Планируемое окончание упаковки</td>
		<td>Статус задачи доставки</td>
		<td>Задача взята в работу</td>
		<td>Номер задачи доставки</td>
		<td>Тип задачи доставки</td>
		<td>Пользователь выполняет</td>
	</tr>	
	<tr style="text-align:center;vertical-align: middle;">
		<td>{$data.Ord_id}</td>
		<td>Отгружен</td>
		<td>{$data.Cost}</td>
		<td>{$data.Delivery_date_from}</td>
		<td>{$data.Delivery_date_to}</td>
		<td>{$data.Shop_name}</td>
		<td>{$data.description_ru}</td>
		<td>{$data.Contact_address}</td>
		<td>{$data.Contact_name}</td>
		<td>{$data.Contact_phone}</td>
		<td>{$data.Ord_date}</td>
		<td>{$smarty.now|date_format}</td>
		<td>{$data.varState}</td>
		<td>{$data.varStart}</td>
		<td>{$data.intID}</td>
		<td>Упаковка заказа (доставка по городу)</td>
		<td></td>
	</tr>
</table>