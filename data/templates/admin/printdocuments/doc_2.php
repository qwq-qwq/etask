<?php
	/*
	 * Документ "Податкова накладна з ПДВ"
	 * Документ "Податкова накладна без ПДВ"
	 *
	 **/
	$org = 0;
	if (!empty($order['Organization_name'])) $org = 1;

	$bc = count($bill);
	if($bc>=2){
		$orderid = $bill[$with_nds]['intBillID'];
		$orddate = strtotime($bill[$with_nds]['varTime']);
	}else{
		$orderid = $bill[0]['intBillID'];
		$orddate = strtotime($bill[0]['varTime']);
	}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>

	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE><?php
		if ($with_nds) echo 'Податкова накладна з ПДВ';
		else echo 'Податкова накладна без ПДВ';
	?></TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 3.1  (Win32)">
	<META NAME="CREATED" CONTENT="20061004;14420300">
	<META NAME="CHANGEDBY" CONTENT="User">
	<META NAME="CHANGED" CONTENT="20091031;18500100">

	<link type="text/css" href="css/datepicker.css" rel="stylesheet" />
	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$.datepicker.regional['my'] = { // Default regional settings
			clearText: 'Очистить', // Display text for clear link
			clearStatus: 'Стереть текущую дату', // Status text for clear link
			closeText: 'Закрыть', // Display text for close link
			closeStatus: 'Закрыть без сохранения', // Status text for close link
			prevText: '&#x3c;Пред', // Display text for previous month link
			prevStatus: 'Предыдущий месяц', // Status text for previous month link
			nextText: 'След&#x3e;', // Display text for next month link
			nextStatus: 'Следующий месяц', // Status text for next month link
			currentText: 'Сегодня', // Display text for current month link
			currentStatus: 'Текущий месяц', // Status text for current month link
			monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
			'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'], // Names of months for drop-down and formatting
			monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'], // For formatting
			monthStatus: 'Показать другой месяц', // Status text for selecting a month
			yearStatus: 'Показать другой год', // Status text for selecting a year
			weekHeader: 'Нед', // Header for the week of the year column
			weekStatus: 'Неделя года', // Status text for the week of the year column
			dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], // For formatting
			dayNamesShort: ['Вск', 'Пнд', 'Втр', 'Срд', 'Чтр', 'Птн', 'Суб'], // For formatting
			dayNamesMin: ['Вс','Пд','Вт','Ср','Чт','Пт','Сб'], // Column headings for days starting at Sunday
			dayStatus: 'Установить первым днем недели', // Status text for the day of the week selection
			dateStatus: 'Выбрать день, месяц, год', // Status text for the date selection
			dateFormat: 'dd.mm.yy', // See format options on parseDate
			firstDay: 1, // The first day of the week, Sun = 0, Mon = 1, ...
			initStatus: 'Выбрать дату', // Initial Status text on opening
			isRTL: false // True if right-to-left language, false if left-to-right
		};
		$.datepicker.setDefaults($.datepicker.regional['my']);

		$('#bill_date').datepicker({dateFormat:'dd.mm.yy'});
	});
	</script>

	<STYLE>
	@media print {
		.noPrint {
		    display:none;
		}
	}
	</STYLE>
	<STYLE>
		<!--
		BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-family:"Arial Cyr"; font-size:x-small }
		 -->
	</STYLE>

</HEAD>

<BODY TEXT="#000000" style="margin:0; padding: 0">
<div class="noPrint" style="background-color: silver; border-bottom: 1px solid #999; font-size: 12px; width: 100%; color: black; height: 30px; padding: 5px 5px 2px 8px; position: fixed">
<form method="GET">
<input type="text" name="bill_date" id="bill_date" style="width: 90px; text-align: center" value="<?=date("d.m.Y",$orddate)?>">
<input type="submit" value="Сохранить">
<input type="hidden" value="<?=$_REQUEST['ord']?>" name="ord">
<input type="hidden" value="<?=$_REQUEST['doc']?>" name="doc">
<input type="hidden" value="<?=$_REQUEST['task']?>" name="task">
<input type="hidden" value="<?=$orderid?>" name="intBillID">
<input type="hidden" value="UpdateBillDate" name="event">
<input style="margin-left: 30px" type="button" value="Печать" onclick="window.print()">
</form>
</div>
<div style="height: 38px" class="noPrint"></div>
<TABLE FRAME=VOID CELLSPACING=0 COLS=37 RULES=NONE BORDER=0>
	<COLGROUP><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=23><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18><COL WIDTH=18></COLGROUP>
	<TBODY>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=4 ROWSPAN=4 WIDTH=72 HEIGHT=56 ALIGN=CENTER VALIGN=MIDDLE>ОРИГІНАЛ </TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=8 WIDTH=144 ALIGN=LEFT>Видається покупцю&nbsp;</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 WIDTH=36 ALIGN=CENTER VALIGN=MIDDLE>X</TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=23 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD WIDTH=18 ALIGN=LEFT><BR></TD>
			<TD COLSPAN=9 WIDTH=162 ALIGN=LEFT>ЗАТВЕРДЖЕНО</TD>
			</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=8 ALIGN=LEFT>Включено до ЄРПН&nbsp;</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><B> </B></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=9 ALIGN=LEFT>Наказ Державної податкової </TD>
			</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=8 ROWSPAN=2 ALIGN=LEFT>Залишається у продавця (тип причини)&nbsp;</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><B> </B></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=9 ALIGN=LEFT>адміністрації України </TD>
			</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><B> </B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><B> </B></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=9 ALIGN=LEFT>21.12.2010 N 969</TD>
			</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=12 HEIGHT=14 ALIGN=LEFT>КОПІЯ (залишається у продавця)</TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=11 ALIGN=LEFT><BR></TD>
			<TD COLSPAN=9 ALIGN=LEFT>(Потрібне виділити поміткою &quot;Х&quot;)</TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=7 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=37 HEIGHT=17 ALIGN=CENTER VALIGN=MIDDLE><B><FONT SIZE=2>ПОДАТКОВА НАКЛАДНА</FONT></B></TD>
			</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=10 HEIGHT=16 ALIGN=LEFT VALIGN=MIDDLE>Дата виписки податкової накладної</TD>
			<TD HEIGHT=23 ALIGN=CENTER COLSPAN=8>
				<TABLE CELLPADDING=0 CELLSPACING=0 >
					<TBODY>
						<TR>
						<?php
							$date = date("dmY",$orddate);
							for ($i=0; $i<strlen($date); $i++):
						?>
							<TD STYLE="border: 2px solid #000000; <?php if ($i != 0) echo 'border-left: 0; ' ?>padding: 0 6px;"><?=$date[$i]?></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=5 ALIGN=RIGHT VALIGN=MIDDLE>Порядковий номер</TD>
			<TD HEIGHT=23 ALIGN=CENTER COLSPAN=7>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
						<?php
							$ordnum = sprintf("%d","$orderid");
							for ($j=0; $j<(7-strlen($ordnum)); $j++):
						?>
							<TD STYLE="border: 2px solid #000000; <?php if ($j != 0) echo 'border-left: 0; ' ?>padding: 0 6px;" WIDTH=6 ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
						<?php endfor; ?>
						<?php

							for ($i=0; $i<strlen($ordnum); $i++):
						?>
							<TD STYLE="border: 2px solid #000000; border-left: 0;padding: 0 6px;"><?=$ordnum[$i]?></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=CENTER VALIGN=MIDDLE><B>/</B></TD>
			<TD HEIGHT=23 ALIGN=CENTER COLSPAN=7>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
							<TD WIDTH=12 STYLE="border: 2px solid #000000; padding: 0 6px;" ALIGN=LEFT><BR></TD>
							<TD WIDTH=12 STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=LEFT>2</TD>
							<TD WIDTH=12 STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=LEFT>1</TD>
							<TD WIDTH=12 STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>0</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD HEIGHT=11 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=4 ALIGN=CENTER VALIGN=TOP>(номер філії)</TD>
			</TR>
		<TR>
			<TD HEIGHT=11 ALIGN=LEFT><BR></TD>
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
			<TD COLSPAN=4 ALIGN=LEFT>Продавець</TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=3 ALIGN=LEFT>Покупець</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=14 ALIGN=LEFT>Особа (платник  </TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=12 ROWSPAN=3 ALIGN=LEFT VALIGN=TOP><?php echo($pattern['2_COMPANY_NAME'])?></TD>
			<TD COLSPAN=6 ALIGN=LEFT>Особа (платник </TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=12 ROWSPAN=3 ALIGN=LEFT VALIGN=TOP><?php
				if ($org) echo $order['Organization_name'];
				else echo $order['Contact_name'];
			?></TD>
			</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=14 ALIGN=LEFT>податку) -продавець</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=6 ALIGN=LEFT>податку) -покупець</TD>
			</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			</TR>
		<TR>
			<TD HEIGHT=24 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=12 ALIGN=CENTER VALIGN=TOP>(найменування; прізвище, ім'я, по батькові - для фізичної особи - підприємця)</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=12 ALIGN=CENTER VALIGN=TOP>(найменування; прізвище, ім'я, по батькові - для фізичної особи - підприємця)</TD>
			</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD HEIGHT=23 ALIGN=CENTER COLSPAN=12>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
						<?php
							for ($i=0; $i<strlen($empik_tax_numb); $i++):
						?>
							<TD STYLE="border: 2px solid #000000; <?php if ($i != 0) echo 'border-left: 0; ' ?>padding: 0 6px;"><?=$empik_tax_numb[$i]?></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER COLSPAN=12>
			<?php if (strlen($order['Tax_number']) > 0): ?>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
						<?php
							for ($i=0; $i<strlen($order['Tax_number']); $i++):
						?>
							<TD STYLE="border: 2px solid #000000; <?php if ($i != 0) echo 'border-left: 0; ' ?>padding: 0 6px;"><?=$order['Tax_number'][$i]?></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			<?php endif; ?>
			</TD>
		</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=12 ALIGN=LEFT VALIGN=TOP>(індивідуальний податковий номер продавця)</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=12 ALIGN=LEFT VALIGN=TOP>(індивідуальний податковий номер покупця)</TD>
			</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=13 ALIGN=LEFT>Місцезнаходження</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=12 ROWSPAN=3 ALIGN=LEFT VALIGN=TOP><?php echo($pattern['2_COMPANY_ADDRES'])?></TD>
			<TD COLSPAN=6 ALIGN=LEFT>Місцезнаходження</TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=12 ROWSPAN=3 ALIGN=LEFT VALIGN=TOP><?php
				if ($org) echo $order['Org_address'];
				else echo $order['Contact_address'];
			?></TD>
			</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=13 ALIGN=LEFT>(податкова адреса)</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=6 ALIGN=LEFT>(податкова адреса)</TD>
			</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=12 ALIGN=LEFT>продавця</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=6 ALIGN=LEFT>покупця  </TD>
			</TR>
		<TR>
			<TD HEIGHT=7 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=14 ALIGN=LEFT>Номер телефону </TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT COLSPAN=10>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
							<TD STYLE="border: 2px solid #000000; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>0</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>4</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>4</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>3</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>5</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>9</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>0</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" WIDTH=12 ALIGN=CENTER VALIGN=MIDDLE>0</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" WIDTH=12 ALIGN=CENTER VALIGN=MIDDLE>2</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" WIDTH=12 ALIGN=CENTER VALIGN=MIDDLE>7</TD>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=6 ALIGN=LEFT>Номер телефону </TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT COLSPAN=10>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
						<?php
							$pnumber = preg_replace("|[^\d]|","",$order['Contact_phone']);
							for ($i=0; $i<strlen($pnumber); $i++):
						?>
							<TD STYLE="border: 2px solid #000000; <?php if ($i != 0) echo 'border-left: 0; ' ?>padding: 0 6px;"><?=$pnumber[$i]?></TD>
						<?php endfor; ?>
						<?php
							for ($j=$i; $j<10; $j++):
						?>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" WIDTH=6 ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=7 ROWSPAN=4 HEIGHT=48 ALIGN=LEFT>Номер свідоцтва про реєстрацію платника податку на додану вартість (продавця)</TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=7 ROWSPAN=4 ALIGN=LEFT>Номер свідоцтва про реєстрацію платника податку на додану вартість (покупця)</TD>
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
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT COLSPAN=10>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY> 
						<TR>
							<TD STYLE="border: 2px solid #000000; padding: 0 6px;" WIDTH=6 ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" WIDTH=6 ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>2</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>0</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>0</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>0</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>0</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>1</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>1</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>5</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>3</TD>

						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=CENTER COLSPAN=10>
				<TABLE CELLPADDING=0 CELLSPACING=0 HEIGHT=14>
					<TBODY>
						<TR>
						<?php
							$pnumber = $order['Vat_certificate'];
							for ($j=0; $j<(10-strlen($pnumber)); $j++):
						?>
							<TD STYLE="border: 2px solid #000000; <?php if ($j != 0) echo 'border-left: 0; ' ?>padding: 0 6px;" WIDTH=6 ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
						<?php endfor; ?>
						<?php
							for ($i=0; $i<strlen($pnumber); $i++):
						?>
							<TD WIDTH=10 STYLE="border: 2px solid #000000; <?php if ($i != 0) echo 'border-left: 0; ' ?>padding: 0 6px;"><?=$pnumber[$i]?></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD ALIGN=LEFT COLSPAN=23><BR></TD>
		</TR>
		<TR>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=14 ALIGN=LEFT>Вид цивільно-</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=12 ALIGN=LEFT VALIGN=TOP>Купівлі-продажу</TD>
			<TD ALIGN=RIGHT>від</TD>
			<TD ALIGN=LEFT COLSPAN=7>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
						<?php
							$date = date("dmY",$orddate);
							for ($i=0; $i<strlen($date); $i++):
						?>
							<TD STYLE="border: 2px solid #000000; <?php if ($i != 0) echo 'border-left: 0; ' ?>padding: 0 6px;"><?=$date[$i]?></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT>№</TD>
			<TD ALIGN=LEFT COLSPAN=7>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TBODY>
						<TR>
							<TD STYLE="border: 2px solid #000000; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>Р</TD>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;" ALIGN=CENTER VALIGN=MIDDLE>Н</TD>
						<?php
							$date = sprintf("%07d",$order['Ord_id']);
							for ($i=0; $i<strlen($date); $i++):
						?>
							<TD STYLE="border: 2px solid #000000; border-left: 0; padding: 0 6px;"><?=$date[$i]?></TD>
						<?php endfor; ?>
						</TR>
					</TBODY>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD COLSPAN=6 HEIGHT=14 ALIGN=LEFT>правового договору  </TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT VALIGN=TOP COLSPAN=5>(вид договору) </TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=6 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=8 HEIGHT=14 ALIGN=LEFT>Форма проведених розрахунків  </TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=29 ALIGN=LEFT>оплата з поточного рахунку</TD>
			</TR>
		<TR>
			<TD HEIGHT=11 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=14 ALIGN=LEFT>(бартер, готівка, оплата з поточного рахунку, чек тощо)</TD>
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
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=37>
				<TABLE CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 HEIGHT=132 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Розділ</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Дата відванта-ження (виконання, постачання (оплати*) товарів/послуг)</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Номенклатура постачання товарів/послуг продавця</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Одиниця виміру товару</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Кількість (об'єм, обсяг)</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Ціна постачання одиниці продукції без урахування ПДВ</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=4 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Обсяги постачання (база оподаткування) без урахування ПДВ, що підлягають оподаткуванню за ставками</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Загальна сума коштів, що підлягає оплаті</FONT></TD>
					</TR>
					<TR>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="0,2" SDNUM="1058;0;0%"><FONT SIZE=1 STYLE="margin-right: 5px;margin-left: 5px;">20%</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>0 % (постачання на митній території України)</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>0 % (експорт)</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Звіль-нення від ПДВ </FONT></TD>
						</TR>
					<TR>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT=17 ALIGN=CENTER VALIGN=MIDDLE SDVAL="1" SDNUM="1058;"><FONT SIZE=1>1</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="2" SDNUM="1058;"><FONT SIZE=1>2</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="3" SDNUM="1058;"><FONT SIZE=1>3</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="4" SDNUM="1058;"><FONT SIZE=1>4</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="5" SDNUM="1058;"><FONT SIZE=1>5</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="6" SDNUM="1058;"><FONT SIZE=1>6</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="7" SDNUM="1058;"><FONT SIZE=1>7</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="8" SDNUM="1058;"><FONT SIZE=1>8</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="9" SDNUM="1058;"><FONT SIZE=1>9</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="10" SDNUM="1058;"><FONT SIZE=1>10</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="11" SDNUM="1058;"><FONT SIZE=1>11</FONT></TD>
					</TR>
			<?php
				$count_goods_with_vat = 0;
				$summ_with_vat = 0;
				$summ_without_vat = 0;
				foreach ($goods as $v) {
					if ($v['Vat'] != 0) {
							$count_goods_with_vat++;
							$summ_with_vat += ($v['Price']-$v['Price']*$v['Vat']/(100+$v['Vat']))*$v['Qty'];
					} else $summ_without_vat += $v['Price']*$v['Qty'];
				}
				// Также учитываем доставку как товар с НДС
				if (!empty($order['Overcost']) && $order['Overcost'] > 0) {
					$count_goods_with_vat++;
					$summ_with_vat += ($order['Overcost']-$order['Overcost']*20/(120));

					$goods[$numb] = array(
						'Price'=>$order['Overcost'],
						'Vat'=>20,
						'Qty'=>1,
						'Name'=>'Доставка'
					);
				}

				$numb = 0;
				foreach ($goods as $v):
					if ( ($with_nds && $v['Vat']!=0) || (!$with_nds && $v['Vat']==0) ):

						$nds = $v['Price']*$v['Vat']/(100+$v['Vat']);
						$price = $v['Price']-$nds;

						$sum += $price*$v['Qty'];
						$sum_nds += $nds*$v['Qty'];
			?>
					<TR>
				<?php if ($numb==0): ?>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=<?php if ($with_nds) echo $count_goods_with_vat+1; else echo (count($goods)-$count_goods_with_vat+1); ?> HEIGHT=17 ALIGN=CENTER VALIGN=TOP><FONT SIZE=1>I</FONT></TD>
				<?php endif; ?>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE SDVAL="40096" SDNUM="1058;0;DD/MM/YY"><FONT SIZE=1><?php echo date("d/m/Y",$orddate) ?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1><?=$v['Name']?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><?php if ($v['Name'] == 'Доставка') echo 'посл.'; else echo 'шт.'; ?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="1" SDNUM="1058;0;#&nbsp;##0,000"><FONT SIZE=1 STYLE="margin-right: 5px;"><?=number_format($v['Qty'], 2, ',', ' ')?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="30" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							echo number_format($price, 2, ',', ' ');
						?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><?php
							if ($with_nds) echo number_format($price*$v['Qty'], 2, ',', ' ');
						?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="30" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							if (!$with_nds) echo number_format($price*$v['Qty'], 2, ',', ' ');
						?></FONT></TD>
				<?php if ($numb++==0): ?>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ROWSPAN=<?php if ($with_nds) echo $count_goods_with_vat+1; else echo (count($goods)-$count_goods_with_vat+1); ?> ALIGN=RIGHT VALIGN=BOTTOM SDVAL="40" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							if (!$with_nds) echo number_format($summ_without_vat, 2, ',', ' ');
							else echo number_format($summ_with_vat, 2, ',', ' ');
						?></FONT></TD>
				<?php endif; ?>
					</TR>
			<?php
					endif;
				endforeach;
			?>
					<TR>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Усього по розділу I</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,000"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="0" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							if ($with_nds) echo number_format($sum, 2, ',', ' ');
						?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="40" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							if (!$with_nds) echo number_format($sum, 2, ',', ' ');
						?></FONT></TD>
						</TR>
					<TR>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT=17 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>II</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Зворотна (заставна) тара</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Х</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Х</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Х</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Х</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Х</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Х</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Х</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
					</TR>
					<TR>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT=30 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>III</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Податок на додану вартість</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="0" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							if ($with_nds) echo number_format($sum_nds, 2, ',', ' ');
						?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><?=($v['Vat']==0) ? "без ПДВ" : "<BR>"?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="0" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							echo number_format($sum_nds, 2, ',', ' ');
						?></FONT></TD>
					</TR>
					<TR>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT=17 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>IV</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=2 ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Загальна сума з ПДВ</FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="0" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							if ($with_nds) echo number_format($sum+$sum_nds, 2, ',', ' ');
						?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT VALIGN=MIDDLE SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1><BR></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="40" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							if (!$with_nds) echo number_format($sum, 2, ',', ' ');
						?></FONT></TD>
						<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE SDVAL="40" SDNUM="1058;0;#&nbsp;##0,00"><FONT SIZE=1 STYLE="margin-right: 5px;"><?php
							echo number_format($sum+$sum_nds, 2, ',', ' ');
						?></FONT></TD>
					</TR>
				</TABLE>
			</TD>
		</TR>
		<TR>
			<TD HEIGHT=8 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=37 HEIGHT=24 ALIGN=LEFT>      Суми ПДВ, нараховані (сплачені) в зв'язку з постачанням товарів/послуг,  зазначених у цій накладній, визначені  правильно, відповідають сумі податкових зобов'язань продавця і включені до реєстру виданих та отриманих податкових накладних.</TD>
			</TR>
		<TR>
			<TD HEIGHT=4 ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=14 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-left: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE>МП</TD>
			<TD STYLE="border-top: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=17 ALIGN=RIGHT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE><BR></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-right: 1px solid #000000" ALIGN=LEFT><BR></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=15 ALIGN=LEFT VALIGN=TOP>(підпис і прізвище особи, яка склала податкову накладну) </TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=37 HEIGHT=37 ALIGN=LEFT VALIGN=TOP>    * Дата оплати ставиться у разі попередньої оплати постачання, на яку виписується податкова накладна, для операцій з постачання  товарів/послуг відповідно до пункту 187.10 статті 187 розділу V Податкового кодексу України.</TD>
			</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000" COLSPAN=37 HEIGHT=14 ALIGN=LEFT VALIGN=TOP>   **  &nbsp;(відповідні пункти (підпункти), статті, підрозділи, розділи &nbsp;Податкового кодексу України, якими передбачено звільнення від оподаткування).</TD>
			</TR>
	</TBODY>
</TABLE>
</BODY>

</HTML>