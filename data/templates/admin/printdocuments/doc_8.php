<?php
	/*
	 * Printed document "Лист заказа"
	 *
	 **/
	mb_internal_encoding("UTF-8");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Лист заказа</title>
</head>
<BODY TEXT="#000000" style="font-size:x-small; font-family:Arial; width:900px;">
<TABLE FRAME=VOID CELLSPACING=0 COLS=11 RULES=NONE style="table-layout:fixed;border-collapse:collapse;">
	<COLGROUP><COL WIDTH=50><COL WIDTH=100><COL WIDTH=67><COL WIDTH=67><COL WIDTH=67><COL WIDTH=67><COL WIDTH=50><COL WIDTH=67><COL WIDTH=89><COL WIDTH=89><COL WIDTH=240></COLGROUP>
	<TBODY>
		<TR>
			<TD COLSPAN=2 WIDTH=150 HEIGHT=38 ALIGN=RIGHT VALIGN=MIDDLE><B>Дата замовлення:</B></TD>
			<TD COLSPAN=6 WIDTH=402 ALIGN=LEFT VALIGN=MIDDLE><?=date('d.m.y H:i',strtotime($order['Ord_date']))?></TD>
			<TD COLSPAN=3 ROWSPAN=6 WIDTH=207 ALIGN="center" VALIGN=TOP><BR><IMG SRC="<?=PROJECT_URL?>document-39-<?=$order['Ord_id']?>.png">
			</TD>
			</TR>
		<TR>
			<TD COLSPAN=2 HEIGHT=33 ALIGN=RIGHT VALIGN=MIDDLE><B>Номер замовленя:</B></TD>
			<TD COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE SDVAL="243" SDNUM="1058;"><?=$order['Ord_id']?></TD>
			</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000" COLSPAN=2 HEIGHT=26 ALIGN=RIGHT VALIGN="bottom"><B><FONT SIZE=5>Клієнт:</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN="bottom"><?=$order['Contact_name']?></TD>
			</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #000000" COLSPAN=2 HEIGHT=26 ALIGN=RIGHT VALIGN=MIDDLE><B>Email:</B></TD>
			<TD STYLE="border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><U><FONT COLOR="#0000FF"><A HREF="mailto:<?=$order['Contact_mail']?>"><?=$order['Contact_mail']?></A></FONT></U></TD>
			</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #000000" COLSPAN=2 HEIGHT=17 ALIGN=RIGHT VALIGN=MIDDLE><B>Телефон:</B></TD>
			<TD STYLE="border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?=$order['Contact_phone']?></TD>
			</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #000000" COLSPAN=2 ALIGN=RIGHT VALIGN=MIDDLE><B>Адреса:</B></TD>
			<TD STYLE="border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?=$order['Contact_address']?></TD>
			</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000" COLSPAN=2 HEIGHT=26 ALIGN=RIGHT VALIGN=MIDDLE><B>Форма оплати:</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?=$paym_type['Name_UA']?></TD>
			<TD ROWSPAN=2 ALIGN=LEFT VALIGN=TOP><BR></TD>
			<TD ROWSPAN=2 ALIGN=LEFT VALIGN=TOP><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #000000" COLSPAN=2 HEIGHT=22 ALIGN=RIGHT VALIGN=MIDDLE><B>Стан оплати:</B></TD>
			<TD STYLE="border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?if ($order['Pay_state'] == 1) echo 'Сплачено'; else echo 'Не сплачено';?></TD>
			</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #000000" COLSPAN=2 HEIGHT=24 ALIGN=RIGHT VALIGN=MIDDLE><B>Тип доставки:</B></TD>
			<TD STYLE="border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?=$deliv_type['Name_UA']?></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<?if(strlen($order['Ord_comment']) > 0){?>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000" COLSPAN=2 ALIGN=RIGHT VALIGN=MIDDLE><B>Комент. клієнта :</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?=$order['Ord_comment']?></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<?}?>
		<?if(strlen($order['Adm_comment']) > 0){?>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000" COLSPAN=2 ALIGN=RIGHT VALIGN=MIDDLE><B>Комент. Менедж.:</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?=$order['Adm_comment']?></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<?}?>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" COLSPAN=2 HEIGHT=29 ALIGN=RIGHT VALIGN=MIDDLE><B>Менеджер:</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=6 ALIGN=LEFT VALIGN=MIDDLE><?=$manager['varFIO']?></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" HEIGHT=18 ALIGN=CENTER BGCOLOR="#C0C0C0"><B>№</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER BGCOLOR="#C0C0C0"><B>Артикул</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" COLSPAN=5 ALIGN=CENTER BGCOLOR="#C0C0C0"><B>Найменування</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" ALIGN=CENTER BGCOLOR="#C0C0C0"><B>К-ть</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" ALIGN=CENTER BGCOLOR="#C0C0C0"><B>Ціна за од.</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER BGCOLOR="#C0C0C0"><B>Вартість</B></TD>
		</TR>
		<?
		$list_goods = $goods;
		// Учитываем доставку если она есть
		if ($order['Overcost'] > 0) {
			$numb = count($goods);
			$list_goods[$numb] = array(
				'Price'=>$order['Overcost'],
				'Vat'=>20,
				'Qty'=>1,
				'Name'=>'Доставка'
			);
			if ($order['Delivery_type'] == 1) $list_goods[$numb]['Wares_id'] = 224955;
			elseif ($order['Delivery_type'] == 4) $list_goods[$numb]['Wares_id'] = 225159;
		}
		$total = 0;
		// Выводим товары
		foreach ($list_goods as $k=>$v){
			$total += $v['Price']*$v['Qty'];?>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" HEIGHT=18 ALIGN=CENTER SDVAL="1" SDNUM="1058;"><?=$k+1?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" ALIGN=CENTER SDVAL="271910" SDNUM="1058;"><?=$v['Wares_id']?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" COLSPAN=5 ALIGN=LEFT><?=$v['Name']?><BR><strong><?=$v['Author']?></strong></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" ALIGN=CENTER><?=$v['Qty']?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000;" ALIGN=CENTER><?=number_format($v['Price'], 2, ',', ' ')?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER><?=number_format($v['Price']*$v['Qty'], 2, ',', ' ')?></TD>
		</TR>
		<?}?>
			<TD STYLE="border-top: 1px solid #000000;" HEIGHT=27 ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000;" ALIGN=LEFT><B>Всього</B><br />(Знижка клієнта <?=$order['discount']?>%)</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT><B><FONT SIZE=4><?=number_format($total, 2, ',', ' ')?></FONT></B></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=4 ROWSPAN=2 ALIGN=CENTER><B><FONT SIZE=6>№ <?=$order['Ord_id']?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 HEIGHT=27 ALIGN=RIGHT><U><FONT SIZE=4>ТИП ДОСТАВКИ:</FONT></U></TD>
			<TD COLSPAN=6 ALIGN=CENTER><B><FONT SIZE=4><?=mb_strtoupper($deliv_type['Name_UA'])?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=3 HEIGHT=27 ALIGN=RIGHT><U><FONT SIZE=4>ТИП ОПЛАТИ:</FONT></U></TD>
			<TD COLSPAN=6 ALIGN=CENTER><B><FONT SIZE=4><?=mb_strtoupper($paym_type['Name_UA'])?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border: 1px solid #000000; border-right: none" COLSPAN=3 ALIGN=LEFT>
<!--			<table width="100%" style="margin-left:5px">
			<tr>
				<td align="center" valign="bottom"><?if($transf_numb > 0){?>Видаткова накл. №:<?}?></td>
			</tr>
			<tr>
				<td align="right" valign="middle"><?if($transf_numb > 0){
					echo '<img src="'.PROJECT_URL.'document-39-'.$transf_numb.'.png" />';
				}?></td>
			</tr>
			</table>-->
			</TD>
			<TD STYLE="border: 1px solid #000000; border-left: none" COLSPAN=4 ALIGN=RIGHT>
			</TD>
			<TD STYLE="border: 1px solid #000000; border-left: none;" COLSPAN=6 ALIGN=CENTER>
				<?include(dirname(realpath(__FILE__)).'/courier_barcodes.php');?>
			</TD>
		</TR>

	</TBODY>
</TABLE>
</body>
</html>