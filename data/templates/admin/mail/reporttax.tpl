<table border="1" style="font-size:12px;text-align:center;">
	<tr style="font-weight:bold;text-align:center;">
		<td>Номер заказа</td>
		<td>Название организации</td>
		<td>ИНН</td>
		<td>Номер свидетельства</td>
		<td>ЕГРПОУ</td>
		<td>Плательщик налога (Да/Нет)</td>
		<td>Адрес предприятия</td>
	</tr>
	<tr style="text-align:center;vertical-align: middle;">
		<td>{$data.Ord_id}</td>
		<td>{$data.Organization_name}</td>
		<td>{$data.Tax_number}</td>
		<td>{$data.Vat_certificate}</td>
		<td>{$data.Edrpou}</td>
		<td>{$data.Nds}</td>
		<td>{$data.Org_address}</td>
	</tr>
</table>