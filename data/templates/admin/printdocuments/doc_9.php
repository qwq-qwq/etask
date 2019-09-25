<!--Накладная на внутренне перемещение-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>Накладная на внутренне перемещение</TITLE>
</HEAD>
<style>
table{
	font-family:"Tahoma";
	font-size:10pt;
	border-collapse:collapse;
}
</style>
<BODY TEXT="#000000">
<TABLE FRAME=VOID CELLSPACING=0 COLS=11 RULES=NONE>
	<COLGROUP><COL WIDTH=42><COL WIDTH=118><COL WIDTH=40><COL WIDTH=220><COL WIDTH=75><COL WIDTH=72><COL WIDTH=86><COL WIDTH=105><COL WIDTH=86><COL WIDTH=98><COL WIDTH=123></COLGROUP>
	<TBODY>
		<TR>
			<TD WIDTH=42 HEIGHT=25 ALIGN=LEFT><BR></TD>
			<TD colspan="10" ALIGN=LEFT><B><U><?php echo($pattern['9_FIRMA'])?></U></B> <?php echo($pattern['9_COMPANY_NAME'])?></TD>
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT colspan="10">ЄДРПОУ <?php echo($pattern['9_EDRPU_CODE'])?> , тел <?php echo($pattern['9_COMPANY_TEL'])?></TD>
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT colspan="10"><?php echo($pattern['9_RR_BANK'])?></TD>
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT colspan="10">Адреса <?php echo($pattern['9_COMPANY_ADDRES'])?></TD>
		</TR>
		<TR>
			<TD HEIGHT=24 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT colspan="2" style="font-size:11pt;"><B><U>Склад відправник:</U></B></TD>
			<TD ALIGN=LEFT colspan="8"><?=$transf['from_shop_name']?></TD>
		</TR>
		<TR>
			<TD HEIGHT=24 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT colspan="2" style="font-size:11pt;"><B><U>Склад одержувач:</U></B></TD>
			<TD ALIGN=LEFT colspan="8"><?=$transf['to_shop_name']?></TD>
		</TR>
		<TR>
			<TD HEIGHT=24 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT colspan="2" style="font-size:11pt;"><B><U>Коментар:</U></B></TD>
			<TD ALIGN=LEFT colspan="8"><?=$transf['comment']?></TD>
		</TR>
		<TR>
			<TD HEIGHT=62 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT VALIGN="bottom" colspan="9" style="font-size:14pt;"><B>Накладна на внутрішнє переміщення № </B><?=$transf['intNumberInvoice']?></TD>
		</TR>
		<TR>
			<TD HEIGHT=68 ALIGN=LEFT><BR></TD>
			<td colspan="3"><img style="" src="<?=PROJECT_URL?>document-39-<?=$transf['intNumberInvoice']?>.png" /></td>
			<TD ALIGN=LEFT VALIGN=TOP colspan="8" style="font-size:14pt;">від <?=date('d.m.Y', strtotime($transf['Inv_date']))?> р.</TD>
		</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 HEIGHT=37 ALIGN=CENTER BGCOLOR="#999999"><B>№ п/п</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER BGCOLOR="#999999"><B>Артикул</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=3 ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#999999"><B>Товар</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#999999"><B>Кіл-сть</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#999999"><B>Од.</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#999999"><B>Ціна роздрібна</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#999999"><B>Ціна без ПДВ</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#999999"><B>Сума роздрібна</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#999999"><B>Сума облікова без ПДВ</B></TD>
		</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER BGCOLOR="#999999"><B>ШтрихКод</B></TD>
		</TR>
		<?
		$cc = 1;
		$qty = 0;
		$total_price = 0;
		$total_no_vat = 0;
		foreach ($goods as $article){
			$price_no_vat = $article['Price']*(100 - $article['Vat'])/100;
			$qty += $article['Qty'];
			$total_price += $article['Price']*$article['Qty'];
			$total_no_vat += $price_no_vat*$article['Qty'];
		?>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 HEIGHT=34 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><?=$cc?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER BGCOLOR="#FFFFFF"><?=$article['Wares_id']?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=3 ROWSPAN=2 ALIGN=LEFT VALIGN=MIDDLE BGCOLOR="#FFFFFF"><?=$article['Name']?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><?=$article['Qty']?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF">шт.</TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><?=number_format($article['Price'], 2, ',', ' ')?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><?=number_format($price_no_vat, 2, ',', ' ')?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><?=number_format($article['Price']*$article['Qty'], 2, ',', ' ')?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><?=number_format($price_no_vat*$article['Qty'], 2, ',', ' ')?></TD>
		</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER BGCOLOR="#FFFFFF"><?=(isset($article['Ean']))?$article['Ean']:'&nbsp'?></TD>
		</TR>
		<?
		$cc++;}?>
		<TR>
			<TD HEIGHT=35 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><B>Разом:</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><B><?=$qty?></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><B><BR></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><B>Разом:</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><B><?=number_format($total_price, 2, ',', ' ')?></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#FFFFFF"><B><?=number_format($total_no_vat, 2, ',', ' ')?></B></TD>
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
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT colspan="5">Видав:_________________________</TD>
			<TD ALIGN=LEFT colspan="5">Отримав:_________________________</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>

</HTML>
