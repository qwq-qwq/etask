<!--Лист сбора перемещения-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>Лист сбора перемещения</TITLE>
</HEAD>
<BODY>
<table align="center" width="800" border="1" bordercolor="black" cellpadding="0" cellspacing="0">
    <tr>
       <td>
            <p align="left"><img src="<?=PROJECT_URL?>document-39-<?=$order['Ord_id']?>.png" /></td></p>
        </td>
    </tr>
    <tr>
        <td bgcolor="#999999"  width="800">
            <p align="center"><font face="Verdana" size="6"><b>Замовлення bukva.ua # <?=$order['Ord_id']?></b></font></p>
        </td>
    </tr>
</table>
<table height="10"><tr><td>&nbsp;</td></tr></table>
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
        <td bgcolor="#999999" width="70">&nbsp;</td>
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
            <p align="center"><font face="Verdana" size="2"><?=$article['Qty']?></font></p>
        </td>
        <td>
            <p align="center"><font face="Verdana" size="2"><?=$article['Price']?></font></p>
        </td>
        <td>
            <p align="left"><font face="Verdana" size="2"><?=$article['warehouse']?></font></p>
        </td>
        <td>&nbsp;</td>
</tr>
<?}?>
    <tr>
        <td colspan="4" width="420">
            <p align="right"><b><font face="Verdana" size="2">Всього:</font></b></p>
        </td>
<td align="center"><font face="Verdana" size="2"><?=$t_qty?></font>
</td>
<td align="center"><font face="Verdana" size="2"><?=$t_price?></font>
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
    </tr>

</table>
<table align="center" width="800" border="0" cellpadding="0" cellspacing="0">
<tr>
        <td width="420">
            <p align="right"><font face="Verdana" size="4"><b>Строк виконання:&nbsp;&nbsp;&nbsp;</b></font></p>
        </td>
        <td  width="380">
            <p align="left"><font face="Verdana" size="4"><b><?=$task['intExecutionTime']/3600?> годин</b></font></p>
        </td>

</tr>
<tr>
        <td  width="420">
            <p align="right"><font face="Verdana" size="4"><b>Дата/час початку комплектації:&nbsp;&nbsp;&nbsp;</b></font></p>
        </td>
        <td width="380">
            <p align="left"><font face="Verdana" size="4"><b><?=date('d.m.Y H:i', strtotime($task['varStart']))?></b></font></p>
        </td>

</tr>
<tr>
        <td  width="420">
            <p align="right"><font face="Verdana" size="4"><b>Дата/час закінчення комплектації:&nbsp;&nbsp;&nbsp;</b></font></p>
        </td>
        <td  width="380">
            <p align="left"><font face="Verdana" size="4"><b><?=date('d.m.Y H:i', strtotime($task['varStart']) + $task['intExecutionTime'])?></b></font></p>
        </td>

</tr>
</table>
</BODY>
</HTML>