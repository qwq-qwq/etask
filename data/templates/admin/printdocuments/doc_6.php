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
<tr><td>
  <table width="100%"><tr><td width="50%" VALIGN=TOP style="border: 3px solid black;">
		<div><b style="text-decoration: underline;">Вiдправник:</b></div>
		<div style="margin: 3px; margin-left: 0; font-size: 20px;"><?php echo($pattern['6_COMPANY_ADDRES'])?><br><?php echo($pattern['6_COMPANY_NAME'])?></div>
  </td><td width="50%" valign="top">
	<?php if ($order['Delivery_type'] == 4): ?>
	<div style="margin: 3px; margin-right: 0;">
  	<table width="100%">
		<tr>
			<td valign="top"><b style="text-decoration:underline;">Цiннiсть:</b></td>
			<td valign="top" style="font-size: 26px;"><b>
				<?php echo number_format(ceil($order['Cost']+$order['Overcost']), 2, ',', ' ').' грн.'; ?>
			</b></td>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<?php echo sumtostr(ceil($order['Cost']+$order['Overcost']), 'ua'); ?>
			</td>
		</tr>
		<?php if ($order['Payment_type'] == 6): ?>
		<tr>
			<td valign="top" style="padding-top: 7px;"><b style="text-decoration:underline;">Н.П.:</b></td>
			<td valign="top" style="font-size: 26px; padding-top: 7px;"><b>
				<?php echo number_format(round($order['Cost']+$order['Overcost'], 2), 2, ',', ' ').' грн.'; ?>
			</b></td>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<?php echo sumtostr(round($order['Cost']+$order['Overcost'], 2), 'ua'); ?>
			</td>
		</tr>
		<?php endif; ?>
	</table>
	</div>
	<?php endif; ?>
  </td></tr></tr>
  </table><br />
</td></tr>
<tr>
	<td>
		<table width="100%">
		<tr>
			<td style="text-align: center;">Дата та час пакування: <?php echo date("d.m.Y H:i") ?></td>
			<td width="133">
				<table width="133" style="border: 1px solid black">
				<tr>
					<td align="center" valign="middle"><img src="/img/logo-bottom.gif"></td>
				</tr>
				</table>
			</td>
		</td>
		</table>
		<br />
		<br />
		<br />
	</td>
</tr>
<tr>
	<td>
	<table width="100%">
<?php if ($order['Delivery_type'] == 3): ?>
	<tr>
		<td valign="top" style="font-size: 24px;">Самовивiз:</td>
		<td style="padding-top: 7px;">
			<?='['.$order['Shop_id'].'] ('.$shop_info['Name_UA'].') '.$shop_info['name_ua']?>
		</td>
	</tr>
<?php endif; ?>
	</table>
	</td>
</tr>
<tr><td>
<table width="100%"><tr><td width="30%" VALIGN=TOP><div style="font-size: 24px; text-align: center;">Замовлення bukva.ua №<?=$order['Ord_id']?>:</div><div style="text-align: center;"><?php
					echo '<img src="'.PROJECT_URL.'document-39-'.$order['Ord_id'].'.png" />';
				?></div><div style="text-align: center;">Пакувальник: <?php echo $packer['varFIO']?></div></td><td width="70%" style="border: 3px solid black;" VALIGN=TOP>
		<div><b style="text-decoration: underline;">Отримувач:</b></div><div style="margin: 3px; margin-right: 0; font-size: 30px;"><?php
			if ($org) {
				echo '<div style="text-decoration:underline;">'.$order['Organization_name'].'</div>'.$order['Org_address'];
			} else {
				echo '<div style="text-decoration:underline;">'.$order['Contact_name'].'</div>'.$order['Contact_address'];
			}
		?></div></td></tr>
  </table>
</td></tr>
<?php if ($order['Delivery_type'] == 3): ?>
<tr>
	<td style="border-top: 1px solid #000;">
	<table width="100%">
	<tr>
		<td width="50%" align="left">
			<table width="180">
<!--			<tr>
				<td align="center" valign="bottom">Предаточная накл. №:</td>
			</tr>
			<tr>
				<td align="center" valign="middle"><?php
					echo '<img src="'.PROJECT_URL.'document-39-'.$transf_numb.'.png" />';
				?></td>
			</tr>-->
			</table>
		</td>
		<td width="50%" align="right">
			<table width="250">
			<tr>
				<td align="center" valign="bottom">SPOS, отримування товару №:</td>
			</tr>
			<tr>
				<td align="center" valign="middle"><?php
					echo '<img src="'.PROJECT_URL.'document-13-'.$order['Barcode_pos'].'.png" />';
				?></td>
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