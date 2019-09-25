<?php
	/*
	 * Документ "Етикетка"
	 *
	 **/
	mb_internal_encoding("UTF-8");

	$org = 0;
	if (!empty($order['Organization_name'])) $org = 1;
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Етикетка</title>
</head>
<body>
<style>
html, body {
	margin: 0;
	padding: 10px 2px 2px 2px;
}
body, td {
	font-family: tahoma;
}
table {
	border-collapse: collapse;
}
</style>
<table width="500" style="border: 1px solid black">
<tr>
	<td>
		<table width="100%">
		<tr>
			<td>Дата та час пакування: <?php echo date("d.m.Y H:i") ?></td>
			<td width="133">
				<table width="133" style="border: 1px solid black">
				<tr>
					<td align="center" valign="middle"><img src="/img/logo-bottom.gif"></td>
				</tr>
				</table>
			</td>
		</td>
		</table>
	</td>
</tr>
<tr>
	<td>
	<table width="100%">
	<tr>
		<td width="133">&nbsp;</td>
		<td align="center" valign="middle">

			<table width="400">
			<tr>
				<td align="center" valign="top" style="font-size: 24px;">Замовлення bukva.ua №<?=$order['Ord_id']?>:</td>
			</tr>
			<tr>
				<td align="center" valign="middle"><?php
					echo '<img src="'.PROJECT_URL.'document-39-'.$order['Ord_id'].'.png" />';
				?></td>
			</tr>
			</table>

		</td>
		<td width="133">

		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td>
	<table width="100%">
	<tr>
		<td valign="top" width="130" style="font-size: 24px;">Отримувач:</td>
		<td style="padding-top: 7px;"><?php
			if ($org) {
				echo $order['Organization_name'].'<br />ЄДРПОУ '.$order['Edrpou'].', '.$order['Contact_phone'].'<br />'.'IПН '.$order['Tax_number'].', номер свiдоцтва '.$order['Vat_certificate'].'<BR>';
				if ($order['Nds']) echo 'Є платником податку на прибуток на загальних підставах.';
				else echo 'Не є платником податку на прибуток на загальних підставах.';
				echo '<BR>'.$order['Org_address'];
			} else {
				echo $order['Contact_name'].'<br />'.$order['Contact_phone'].'<br />'.$city.', '.$order['Contact_address'];
			}
		?></td>
	</tr>
	<tr>
		<td valign="top" style="font-size: 24px;">Вiдправник:</td>
		<td style="padding-top: 7px;">
		<?php echo($pattern['7_COMPANY_NAME'])?><br><?php echo($pattern['7_COMPANY_TEL'])?><br><?php echo($pattern['7_COMPANY_ADDRES'])?>
		</td>
	</tr>
<?php if ($order['Delivery_type'] == 3): ?>
	<tr>
		<td valign="top" style="font-size: 24px;">Самовивiз:</td>
		<td style="padding-top: 7px;">
			<?='['.$order['Shop_id'].'] ('.$shop_info['Name_UA'].') '.$shop_info['name_ua']?>
		</td>
	</tr>
<?php endif; ?>
<?php if ($order['Delivery_type'] == 4): ?>
	<tr>
		<td valign="top" style="font-size: 24px;">Цiннiсть:</td>
		<td style="padding-top: 7px;">
			<?php echo sumtostr(round($order['Cost']+$order['Overcost'], 2), 'ua'); ?>
		</td>
	</tr>
	<tr>
		<td valign="top" style="font-size: 24px;"><BR></td>
		<td style="padding-top: 7px; font-size: 24px;">
			<?php echo number_format(round($order['Cost']+$order['Overcost'], 2), 2, ',', ' ').' грн.'; ?>
		</td>
	</tr>
	<?php if ($order['Payment_type'] == 6): ?>
	<tr>
		<td valign="top" style="font-size: 24px;">Н.П.:</td>
		<td style="padding-top: 7px; font-size: 24px;">
			<?php echo number_format(round($order['Cost']+$order['Overcost'], 2), 2, ',', ' ').' грн.'; ?>
		</td>
	</tr>
	<tr>
		<td valign="top" style="font-size: 24px;"><BR></td>
		<td style="padding-top: 7px;">
			<?php echo sumtostr(round($order['Cost']+$order['Overcost'], 2), 'ua'); ?>
		</td>
	</tr>
	<?php endif; ?>
<?php endif; ?>
	<tr>
		<td colspan="2" style="padding-left: 15px;">
			<table>
				<tr><td align="right"><b>Форма оплати:</b></td><td><?=$paym_type['Name_UA']?></td></tr>
				<tr><td align="right"><b>Тип доставки:</b></td><td><?=$deliv_type['Name_UA']?></td></tr>
				<tr><td align="right"><b>Кiлькiсть товарiв:</b></td><td><?=count($goods)?> шт</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 24px;">Вага: ____ , ____<?php //number_format($weight/1000, 1, ',', ' ')?> кг</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 24px; border-bottom: 1px solid #000;">Пакувальник: <?php echo $packer['varFIO']?></td>
	</tr>
	</table>
	</td>
</tr>
<?php if ($order['Barcode_pos'] > 0 || $transf_numb > 0): ?>
<tr>
	<td>
	<table width="100%">
	<tr>
		<td width="260" align="right">
			<!--<table width="100%">
			<tr>
				<td align="center" valign="bottom"><?if($transf_numb > 0){?>Предаточная накл. №:<?}?></td>
			</tr>
			<tr>
				<td align="right" valign="middle"><?php if($transf_numb > 0){
					echo '<img src="'.PROJECT_URL.'document-39-'.$transf_numb.'.png" />';
				}?></td>
			</tr>
			</table>-->
		</td>
		<td width="240" align="left">
			<table width="100%">
			<tr>
				<td align="center" valign="bottom"><?if ($order['Barcode_pos'] > 0){?>SPOS, отримування товару №:<?}?></td>
			</tr>
			<tr>
				<td align="center" valign="middle"><?php if ($order['Barcode_pos'] > 0){
					echo '<img src="'.PROJECT_URL.'document-13-'.$order['Barcode_pos'].'.png" />';
				}?></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
<?php endif; ?>
</table>
</body>
</html>