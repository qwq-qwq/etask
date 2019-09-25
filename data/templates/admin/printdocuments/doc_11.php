<!--Лист сбора заказа-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>Лист сбора заказа</TITLE>
</HEAD>
<BODY>
<table align="center" width="800" border="1" bordercolor="black" cellpadding="0" cellspacing="0">
    <tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2" size="2"><b>Дата замовлення:</b></font></p>
        </td>
        <td width="400">
            <p align="left"><font face="Verdana" size="2"><?=date('d.m.Y H:i', strtotime($order['Ord_date']))?></font></p>
        </td>
        <td rowspan="5" width="190">
            <p align="center"><img src="<?=PROJECT_URL?>document-39-<?=$order['Ord_id']?>.png" /></td></p>
        </td>
    </tr>
<tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Клієнт:</b></font></p>
        </td>
        <td width="400">
            <p align="left"><font face="Verdana" size="2"><?=$order['Contact_name']?></font></p>
        </td>
</tr>
<tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Email:</b></font></p>
        </td>
        <td width="400">
            <p align="left"><font face="Verdana" size="2"><?=$order['Contact_mail']?></font></p>
        </td>
</tr>
<tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Телефон:</b></font></p>
        </td>
        <td width="400">
            <p align="left"><font face="Verdana" size="2"><?=$order['Contact_phone']?></font></p>
        </td>
</tr>
<tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Адреса:</b></font></p>
        </td>
        <td width="400">
            <p align="left"><font face="Verdana" size="2"><?=$order['Contact_address']?></font></p>
        </td>
</tr>
    <tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Форма оплати:</b></font></p>
        </td>
        <td colspan="2" width="550">
            <p align="left"><font face="Verdana" size="2"><?=$paym_type['Name_UA']?></font></p>
        </td>
    </tr>
    <tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Стан оплати:</b></font></p>
        </td>
        <td colspan="2" width="550">
            <p align="left"><font face="Verdana" size="2"><?if ($order['Pay_state'] == 1) echo 'Сплачено';else echo 'Не сплачено';?></font></p>
        </td>
    </tr>
    <tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Тип доставки:</b></font></p>
        </td>
        <td colspan="2" width="550">
            <p align="left"><font face="Verdana" size="2"><?=$deliv_type['Name_UA']?></font></p>
        </td>
    </tr>
    <tr>
        <td width="250">
            <p align="left"><font face="Verdana" size="2"><b>Магазин комплектації:</b></font></p>
        </td>
        <td colspan="2" width="550">
            <p align="left"><font face="Verdana" size="2"><?=$shop_name?></font></p>
        </td>
    </tr>
    <tr>
        <td colspan="3" bgcolor="#999999"  width="800">
            <p align="center"><font face="Verdana" size="6"><b>Замовлення bukva.ua # <?=$order['Ord_id']?></b></font></p>
        </td>
    </tr>
</table>
<table align="center" width="800" border="0" cellpadding="10" cellspacing="0">
<tr><td align="center"><font face="Verdana" size="3"><b>Лист збору товарів для комплектації замовлення</b></font></td></tr>
</table>
<table align="center" width="800" border="1" bordercolor="black" cellpadding="0" cellspacing="0">
<tr>
        <td bgcolor="#999999" width="30">
            <p align="left"><font face="Verdana" size="2"><b>#</b></font></p>
        </td>
        <td bgcolor="#999999" width="70">
            <p align="left"><font face="Verdana" size="2"><b>Арт</b></font></p>
        </td>
        <td bgcolor="#999999" width="250">
            <p align="left"><font face="Verdana" size="2"><b>Назва товару</b></font></p>
        </td>
        <td bgcolor="#999999" width="70">
            <p align="left"><font face="Verdana" size="2"><b>Група</b></font></p>
        </td>
        <td bgcolor="#999999" width="70">
            <p align="left"><font face="Verdana" size="2"><b>Кіл-ть</b></font></p>
        </td>
        <td bgcolor="#999999" width="70">
            <p align="left"><font face="Verdana" size="2"><b>Ціна</b></font></p>
        </td>
        <td bgcolor="#999999" width="240">
            <p align="left"><font face="Verdana" size="2"><b>Місцезнаходження товару</b></font></p>
        </td>
        <td bgcolor="#999999" width="70">
        	<table width="20" border="1" bordercolor="black" align="center" cellpadding="0" cellspacing="0">
        		<tr><td align="center">x</td></tr>
        	</table>
        </td>
</tr>
<?
$i=1;
$t_qty = 0;
$t_price = 0;
foreach ($goods as $article) {
	$t_qty += $article['Qty'];
	$t_price += $article['Price']*$article['Qty'];
	?>
<tr>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$i++?></font></p>
        </td>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$article['Wares_id']?></font></p>
        </td>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$article['Name']?></font></p>
        </td>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$article['Group_name']?></font></p>
        </td>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$article['Qty']?></font></p>
        </td>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$article['Price']?></font></p>
        </td>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$article['warehouse']?></font></p>
        </td>
        <td>
        	<table width="55" height="30" border="1" bordercolor="black" align="center">
        		<tr><td></td></tr>
        	</table>
        </td>
</tr>
<?}?>
    <tr>
        <td colspan="4" width="420">
            <p align="right"><b><font face="Verdana" size="2">Всього:</font></b></p>
        </td>
<td><font face="Verdana" size="2"><?=$t_qty?></font>
</td>
<td><font face="Verdana" size="2"><?=$t_price?></font>
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
    </tr>

</table>
<table align="center" width="800" border="0" cellpadding="0" cellspacing="0">
<tr>
        <td width="420">
            <p align="right"><font face="Verdana" size="4"><b>Строк збору товарів:&nbsp;&nbsp;&nbsp;</b></font></p>
        </td>
        <td  width="380">
            <p align="left"><font face="Verdana" size="4"><b><?=$task['intExecutionTime']/3600?> годин</b></font></p>
        </td>

</tr>
<tr>
        <td  width="420">
            <p align="right"><font face="Verdana" size="4"><b>Дата/час початку збору:&nbsp;&nbsp;&nbsp;</b></font></p>
        </td>
        <td width="380">
            <p align="left"><font face="Verdana" size="4"><b><?=date('d.m.Y H:i', strtotime($task['varCreation']))?></b></font></p>
        </td>

</tr>
<tr>
        <td  width="420">
            <p align="right"><font face="Verdana" size="4"><b>Дата/час закінчення збору:&nbsp;&nbsp;&nbsp;</b></font></p>
        </td>
        <td  width="380">
            <p align="left"><font face="Verdana" size="4"><b><?=date('d.m.Y H:i', strtotime($task['varCreation']) + $task['intExecutionTime'])?></b></font></p>
        </td>

</tr>
</table>
</BODY>
</HTML>