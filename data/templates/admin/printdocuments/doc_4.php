<?php
	/*
	 * Документ "Прибутковий касовий ордер"
	 *
	 **/
	$org = 0;
	if (!empty($order['Organization_name'])) $org = 1;
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
	
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE>Прибутковий касовий ордер</TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 3.1  (Win32)">
	<META NAME="CREATED" CONTENT="20080910;9172700">
	<META NAME="CHANGEDBY" CONTENT="User">
	<META NAME="CHANGED" CONTENT="20091031;18511200">
	
	<STYLE>
		<!-- 
		BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-family:"Arial"; font-size:xx-small }
		 -->
	</STYLE>
	
</HEAD>

<BODY TEXT="#000000">
<TABLE FRAME=VOID ALIGN=LEFT CELLSPACING=0 COLS=18 RULES=NONE BORDER=0>
	<COLGROUP><COL WIDTH=62><COL WIDTH=76><COL WIDTH=27><COL WIDTH=60><COL WIDTH=39><COL WIDTH=103><COL WIDTH=44><COL WIDTH=32><COL WIDTH=25><COL WIDTH=6><COL WIDTH=5><COL WIDTH=54><COL WIDTH=56><COL WIDTH=106><COL WIDTH=61><COL WIDTH=61><COL WIDTH=61><COL WIDTH=61></COLGROUP>
	<TBODY>
		<TR>
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000" COLSPAN=4 WIDTH=264 HEIGHT=15 ALIGN=LEFT VALIGN=TOP><?php echo($pattern['4_COMPANY_NAME'])?></TD>
			<TD STYLE="border-top: 3px solid #000000" COLSPAN=5 ROWSPAN=2 WIDTH=203 ALIGN=RIGHT VALIGN=TOP><?php echo($pattern['4_FORMA_KO1'])?></TD>
			<TD STYLE="border-top: 3px solid #000000" WIDTH=6 ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 3px solid #000000" WIDTH=5 ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 WIDTH=217 ALIGN=LEFT VALIGN=TOP><?php echo($pattern['4_COMPANY_NAME'])?></TD>
			<TD WIDTH=61 ALIGN=LEFT><BR></TD>
			<TD WIDTH=61 ALIGN=LEFT><BR></TD>
			<TD WIDTH=61 ALIGN=LEFT><BR></TD>
			<TD WIDTH=61 ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" COLSPAN=5 HEIGHT=15 ALIGN=LEFT VALIGN=TOP><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000; border-right: 3px solid #000000;" ALIGN=CENTER VALIGN=TOP COLSPAN=3 ROWSPAN=2><?php
				echo '<img src="'.PROJECT_URL.'document-39-'.$order['Ord_id'].'.png" />';
			?></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>

		<TR>
			<TD STYLE="border-bottom: 3px solid #000000; border-left: 3px solid #000000" HEIGHT=13 ALIGN=LEFT>Код за ЄДРПОУ</TD>
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><?php echo($pattern['4_EDRPU_CODE'])?></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=CENTER><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT>Код за УКУД</TD>
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=MIDDLE COLSPAN=3><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" COLSPAN=9 HEIGHT=35 ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=4>ПРИБУТКОВИЙ КАСОВИЙ ОРДЕР </FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=4>КВИТАНЦІЯ</FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000" HEIGHT=49 ALIGN=CENTER VALIGN=MIDDLE>Номер доку- мента</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE>Дата складання</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE>Кореспонду- ючий  рахунок, субрахунок</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE>Код аналі- тичного обліку</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE>Сума</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE>Код цільового призна- чення</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=CENTER VALIGN=TOP><B><FONT SIZE=2>до прибуткового касового ордера</FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 1px solid #000000" HEIGHT=30 ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=2><?=sprintf("%07d",$order['Ord_id'])?></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDNUM="1058;0;DD/MM/YYYY"><B><FONT SIZE=2><?php echo date("d/m/Y") ?></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="702" SDNUM="1058;"><B><FONT SIZE=2>702</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00_р_."><B><FONT SIZE=2><?php echo number_format($order['Cost']+$order['Overcost'], 2, ',', ' '); ?></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-left: 3px solid #000000" ALIGN=LEFT COLSPAN=2><B>Прийнято від</B></TD>
			<TD STYLE="border-right: 3px solid #000000" ALIGN=LEFT SDNUM="1058;0;#&nbsp;##0,00_р_."><B><FONT SIZE=2><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=21 ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
			<TD ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT VALIGN=MIDDLE SDNUM="1058;0;0,00&quot; грн.&quot;"><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=CENTER VALIGN=TOP><B><FONT SIZE=3><?php
				if ($org) echo $order['Organization_name'];
				else echo $order['Contact_name'];
			?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=15 ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 3px solid #000000" ALIGN=LEFT VALIGN=TOP><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT VALIGN=TOP><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-right: 3px solid #000000" ALIGN=LEFT VALIGN=TOP><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
			<TD ALIGN=LEFT><B><FONT SIZE=3><BR></FONT></B></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" COLSPAN=2 HEIGHT=21 ALIGN=LEFT><B>Прийнято від</B></TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=7 ALIGN=LEFT><B><FONT SIZE=3><?php
				if ($org) echo $order['Organization_name'];
				else echo $order['Contact_name'];
			?></FONT></B></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 3px solid #000000" ALIGN=LEFT VALIGN=TOP><FONT SIZE=3><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT VALIGN=TOP><FONT SIZE=3><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-right: 3px solid #000000" ALIGN=LEFT VALIGN=TOP><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=3><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=22 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=7 ALIGN=CENTER VALIGN=TOP>прізвище,  ім'я, по-батькові</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000" ALIGN=LEFT><B>Підстава</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 3px solid #000000" ALIGN=LEFT COLSPAN=2><B><FONT SIZE=2>Замовлення №<?=sprintf("%07d",$order['Ord_id'])?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=23 ALIGN=LEFT><B>Підстава</B></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT COLSPAN=8><FONT>Замовлення №<?=sprintf("%07d",$order['Ord_id'])?></FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=CENTER SDNUM="1058;0;#&nbsp;##0,00_р_."><B><FONT SIZE=2><?php echo number_format($order['Cost']+$order['Overcost'], 2, ',', ' '); ?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=21 ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ROWSPAN=2 ALIGN=CENTER><B><FONT SIZE=2><?php echo sumtostr(round($order['Cost']+$order['Overcost'], 2), 'ua'); ?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=23 ALIGN=LEFT><B>Сума</B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" COLSPAN=7 ALIGN=CENTER><B><FONT SIZE=3><?php echo sumtostr(round($order['Cost']+$order['Overcost'], 2), 'ua'); ?></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 3px solid #000000" COLSPAN=6 HEIGHT=23 ALIGN=CENTER><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=CENTER SDVAL="15" SDNUM="1058;"><FONT><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000" ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=CENTER SDVAL="15" SDNUM="1058;"><B><FONT SIZE=2><BR></FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 3px solid #000000" ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=14 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD ALIGN=LEFT>прописом</TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD ALIGN=CENTER><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=CENTER VALIGN=MIDDLE>прописом</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=23 ALIGN=LEFT><B>Дата</B></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><B>Підпис</B></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 3px solid #000000" HEIGHT=22 ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=CENTER><B><FONT SIZE=2><?php echo date("d/m/Y") ?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=22 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT SDNUM="1058;0;[$-FC22]D MMMM YYYY&quot; р.&quot;;@"><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" COLSPAN=3 HEIGHT=25 ALIGN=LEFT><B><FONT SIZE=2>Головний бухгалтер</FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><B><BR></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000" ALIGN=LEFT VALIGN=TOP><FONT SIZE=2>М.П.</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" HEIGHT=15 ALIGN=LEFT><BR></TD>
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
			<TD STYLE="border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=LEFT><B><FONT SIZE=2>Головний бухгалтер</FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 3px solid #000000" COLSPAN=2 HEIGHT=16 ALIGN=LEFT><B><FONT SIZE=2>Одержав касир</FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=3 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-bottom: 3px solid #000000; border-left: 3px solid #000000" HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000; border-left: 3px solid #000000" ALIGN=LEFT VALIGN=TOP><B><FONT SIZE=2>Касир</FONT></B></TD>
			<TD STYLE="border-bottom: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 3px solid #000000; border-right: 3px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
	</TBODY>
</TABLE>
<BR CLEAR=LEFT>
<?include(dirname(realpath(__FILE__)).'/courier_barcodes.php');?>
</BODY>

</HTML>