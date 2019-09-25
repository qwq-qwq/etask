<?php
	/*
	 * Документ "Видаткова накладна"
	 * Документ "Рахунок-фактура"
	 *
	 **/

	$org = 0;
	if (!empty($order['Organization_name'])) $org = 1;
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<TITLE><?php
				if (!isset($new_doc)) echo 'Видаткова накладна';
				else echo 'Рахунок-фактура';
			?></TITLE>
	<META NAME="GENERATOR" CONTENT="OpenOffice.org 3.1  (Win32)">
	<META NAME="CREATED" CONTENT="20090923;11513500">
	<META NAME="CHANGEDBY" CONTENT="User">
	<META NAME="CHANGED" CONTENT="20091031;18120900">
	
	<STYLE>
		<!-- 
		BODY,DIV,TABLE,THEAD,TBODY,TFOOT,TR,TH,TD,P { font-family:"Arial"; font-size:xx-small }
		 -->
	</STYLE>
</HEAD>

<BODY TEXT="#000000">
<TABLE FRAME=VOID CELLSPACING=0 COLS=10 RULES=NONE style="border: none">
	<COLGROUP><COL WIDTH=35><COL WIDTH=124><COL WIDTH=82><COL WIDTH=82><COL WIDTH=31><COL WIDTH=38><COL WIDTH=103><COL WIDTH=153><COL WIDTH=127><COL WIDTH=127></COLGROUP>
	<TBODY>
		<TR>
			<TD WIDTH=35 HEIGHT=17 ALIGN=LEFT VALIGN=TOP><BR></TD>
			<TD WIDTH=124 ALIGN=LEFT VALIGN=TOP><B><U><FONT SIZE=2>Постачальник</FONT></U></B></TD>
			<TD COLSPAN=6 WIDTH=489 ALIGN=LEFT VALIGN=TOP><FONT SIZE=2><?php echo($pattern[$doc_id.'_COMPANY_NAME'])?></FONT></TD>
			<TD WIDTH=127 ALIGN=LEFT VALIGN=TOP COLSPAN=2 ROWSPAN=6><?php
				echo '<img src="'.PROJECT_URL.'document-39-'.$order['Ord_id'].'.png" />';
			?></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT COLSPAN=6><FONT SIZE=2>ЄДРПОУ <?php  echo($pattern[$doc_id.'_EDRPU_CODE'])?>, тел. <?php  echo($pattern[$doc_id.'_COMPANY_TEL'])?></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=6 ALIGN=LEFT><FONT SIZE=2><?php  echo($pattern[$doc_id.'_RR_BANK_MFO'])?></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT COLSPAN=6><FONT SIZE=2><?php  echo($pattern[$doc_id.'_IPN_INN'])?></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=6 ALIGN=LEFT><FONT SIZE=2><?php  echo($pattern[$doc_id.'_E_PLAT_POD'])?></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT COLSPAN=6><FONT SIZE=2>Адреса <?php echo($pattern[$doc_id.'_COMPANY_ADDRES'])?></FONT></TD>
		</TR>
		<TR>
			<TD HEIGHT=5 ALIGN=LEFT><BR></TD>
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
			<TD HEIGHT=17 ALIGN=LEFT VALIGN=TOP><BR></TD>
			<TD ALIGN=LEFT VALIGN=TOP><B><U><FONT SIZE=2>Одержувач</FONT></U></B></TD>
			<TD COLSPAN=6 ALIGN=LEFT VALIGN=TOP><FONT SIZE=2><?php
			if ($org) {
				echo $order['Organization_name'].'<br />ЄДРПОУ '.$order['Edrpou'].', '.$order['Contact_phone'].'<br />'.'IПН '.$order['Tax_number'].', номер свiдоцтва '.$order['Vat_certificate'].'<BR>';
				if ($order['Nds']) echo 'Є платником податку на прибуток на загальних підставах.';
				else echo 'Не є платником податку на прибуток на загальних підставах.';
				echo '<BR>'.$order['Org_address'];
			} else {
				echo $order['Contact_name'].'<br />'.$order['Contact_phone'];
			}
			?></FONT></TD>
			<TD ALIGN=LEFT VALIGN=TOP><BR></TD>
			<TD ALIGN=LEFT VALIGN=TOP><BR></TD>
		</TR>
		<?php if (!$org || ($org && $order['Delivery_type']==3) ): ?>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT VALIGN=TOP><B><U><FONT SIZE=2>Адреса доставки</FONT></U></B></TD>
			<TD ALIGN=LEFT COLSPAN=6><FONT SIZE=2><?=$order['Contact_address']?></FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<?php endif; ?>
		<TR>
			<TD HEIGHT=21 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><B><U><FONT SIZE=2>Платник</FONT></U></B></TD>
			<TD ALIGN=LEFT><FONT SIZE=2>той самий</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=18 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><B><U><FONT SIZE=2>Замовлення</FONT></U></B></TD>
			<TD ALIGN=LEFT><FONT SIZE=2>№РН-<?=sprintf("%07d",$order['Ord_id'])?></FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
	<?php if (!isset($new_doc)): ?>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><B><U><FONT SIZE=2>Умова продажу:</FONT></U></B></TD>
			<TD ALIGN=LEFT COLSPAN=6><FONT SIZE=2><?php
				if ($paym_type['cash']=='no') echo 'Безготiвковий розрахунок';
				else echo 'Готiвковий розрахунок';
			?></FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
	<?php endif; ?>
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
		</TR>
		<TR>
			<TD COLSPAN=8 HEIGHT=21 ALIGN=CENTER><B><FONT SIZE=3><?php
				if (!isset($new_doc)) echo 'Видаткова накладна';
				else echo 'Рахунок-фактура';
			?> № РН-<?=sprintf("%07d",$order['Ord_id'])?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD COLSPAN=8 HEIGHT=21 ALIGN=CENTER><B><FONT SIZE=3>від <?php
				$year = date("Y");
				$month = date("n");
				$date = date("d");
				
				$arrMonth = array(
					1 => 'Ciчня',
					2 => 'Лютого',
					3 => 'Березня',
					4 => 'Квiтня',
					5 => 'Травня',
					6 => 'Червня',
					7 => 'Липня',
					8 => 'Серпня',
					9 => 'Вересня',
				   10 => 'Жовтня',
				   11 => 'Листопада',
				   12 => 'Грудня'
				);
				echo $date.'&nbsp;'.$arrMonth[$month].'&nbsp;'.$year.'&nbsp;р.';
			?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
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
		</TR>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT=17 ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#C0C0C0"><B><FONT SIZE=2>№</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" ALIGN=CENTER BGCOLOR="#C0C0C0"><B><FONT SIZE=2>Артикул</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=3 ALIGN=CENTER BGCOLOR="#C0C0C0"><B><FONT SIZE=2>Найменування</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#C0C0C0"><B><FONT SIZE=2>Од.</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#C0C0C0"><B><FONT SIZE=2>К-ть</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#C0C0C0"><B><FONT SIZE=2>Ціна без ПДВ</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#C0C0C0"><B><FONT SIZE=2>Сума без ПДВ</FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER VALIGN=MIDDLE BGCOLOR="#C0C0C0"><B><FONT SIZE=2>Сума ПДВ</FONT></B></TD>
		</TR>
<?php $summ = 0; $summ_vat = 0;

	// Учитываем доставку если она есть
	if (!empty($order['Overcost']) && $order['Overcost'] > 0) {
		$vat = $order['Overcost']*20/(120);
		$price_without_vat = ($order['Overcost']-$vat);
		
		$numb = count($goods);
		$goods[$numb] = array(
			'Price'=>$order['Overcost'],
			'Vat'=>20,
			'Qty'=>1,
			'Name'=>'Доставка'
		);
		if ($order['Delivery_type'] == 1) $goods[$numb]['Wares_id'] = 224955;
		elseif ($order['Delivery_type'] == 4) $goods[$numb]['Wares_id'] = 225159;
	}
	
	// Выводим товары
	foreach ($goods as $k=>$v):

	// $price_no_vat = round(100*$article['PriceDiscount']/(100 + $article['Vat']),2);

	/*
	$vat = round($v['Price']*$v['Vat']/(100+$v['Vat']),2);
	$price_without_vat = ($v['Price']-$vat);
	$summ_without_vat = $price_without_vat * $v['Qty'];
	$summ_vatt = $vat * $v['Qty'];
*/

	
	//$vat = $v['Price']*$v['Vat']/(100+$v['Vat']);
	//$price_without_vat 	= round(sprintf('%.3f',100*$v['Price']/(100 + $v['Vat'])),2); 
	$price_with_vat 	= round($v['Price'],2); 
	$summ_with_vat 		= $price_with_vat * $v['Qty'];
	$summ_vatt 		= 0;
	$sum_pr			= $v['Price'] * $v['Qty'];
	
	
	$summ += $summ_with_vat;
	$summ_vat += $summ_vatt;
	$summ_pr  += $sum_pr;	
	echo "<!--";
	var_dump($v);
	echo "--!>";
?>
		<TR>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT=14 ALIGN=CENTER SDVAL="1" SDNUM="1058;"><FONT><?=($k+1)?></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER SDNUM="1058;0;@"><FONT><?=$v['Wares_id']?></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=3 ALIGN=LEFT><FONT><?=$v['Name']?></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER><?php if ($v['Name'] == 'Доставка') echo 'посл.'; else echo 'шт.'; ?></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER><FONT><?=number_format($v['Qty'], 2, ',', ' ')?></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER><FONT><?=number_format($price_with_vat, 2, ',', ' ')?></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER SDVAL="57" SDNUM="1058;"><FONT><?=number_format($summ_with_vat, 2, ',', ' ')?></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=CENTER><FONT><?=number_format($summ_vatt, 2, ',', ' ')?></FONT></TD>
		</TR>
<?php
	endforeach;
?>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT><B><FONT SIZE=2>Разом без ПДВ: </FONT></B></TD>
			<TD STYLE="border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT SDVAL="273,59" SDNUM="1058;0;0,00"><B><FONT SIZE=2><?=number_format($summ, 2, ',', ' ')?></FONT></B></TD>
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT><B><FONT SIZE=2>ПДВ: </FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT SDVAL="43,318" SDNUM="1058;0;0,00"><B><FONT SIZE=2><?=number_format($summ_vat, 2, ',', ' ')?></FONT></B></TD>
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT><B><FONT SIZE=2>Всього з ПДВ: </FONT></B></TD>
			<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN=RIGHT SDVAL="316,908" SDNUM="1058;0;0,00"><B><FONT SIZE=2><?=number_format(($summ+$summ_vat), 2, ',', ' ')?></FONT></B></TD>
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
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><FONT SIZE=2>Всього на суму:</FONT></TD>
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
			<TD HEIGHT=20 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT COLSPAN=6><B><FONT SIZE=2><?php echo sumtostr(round(($summ+$summ_vat), 2), 'ua'); ?></FONT></B></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD HEIGHT=19 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><FONT SIZE=2>В т.ч. ПДВ:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD ALIGN=RIGHT SDVAL="43,318" SDNUM="1058;0;0,00"><FONT SIZE=2><?=number_format($summ_vat, 2, ',', ' ')?></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=2>грн.</FONT></TD>
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
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		</TR>
		<TR><?php
			if (!isset($new_doc)):
		?>
			<TD HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT VALIGN=TOP><FONT SIZE=2>Відвантажив(ла)</FONT></TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=2 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT VALIGN=TOP><FONT SIZE=2>Отримав(ла)</FONT></TD>
			<TD STYLE="border-bottom: 1px solid #000000" COLSPAN=2 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
		<?php
			else:
		?>
			<TD HEIGHT=17 ALIGN=LEFT COLSPAN=4><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT VALIGN=TOP><FONT SIZE=2>Виписав(ла)</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>

		<?php
			endif;
		?>
		</TR>
		<TR>
			<TD HEIGHT=17 ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD <?php if (!isset($new_doc)): ?>STYLE="border-top: 1px solid #000000"<?php endif; ?> COLSPAN=2 ALIGN=CENTER><FONT SIZE=2><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #000000" COLSPAN=2 ALIGN=CENTER><FONT SIZE=2><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=2><BR></FONT></TD>
		</TR>
		<?php if (isset($new_doc)): ?>
			<TD HEIGHT=17 ALIGN=LEFT COLSPAN=4><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=4><FONT SIZE=2>Рахунок дійсний до сплати до <?php
				$valid_time = strtotime("+1 week");
			
				$year = date("Y", $valid_time);
				$month = date("n", $valid_time);
				$date = date("d", $valid_time);

				echo $date.'&nbsp;'.$arrMonth[$month].'&nbsp;'.$year.'&nbsp;р.';
			?></FONT></TD>
		<?php endif; ?>
	</TBODY>
</TABLE>
<?if (!isset($new_doc)){?>
<br>
<?
end($goods);
unset($goods[key($goods)]);
include(dirname(realpath(__FILE__)).'/courier_barcodes.php');
}?>
</BODY>

</HTML>