<style>

div, p {
	font-family: arial;
	font-size: 12px;
	font-weight: bold;
}

</style>
<? if (!empty($order['Barcode_pos'])) { ?>
<div>
	<p>Штрих-код для SPOS:</p>
	<img style="margin-top:-10px;" src="<?=PROJECT_URL?>document-13-<?=$order['Barcode_pos']?>.png" />
</div>
<? } ?>
<?
/*
$summ_with_vat = 0;
$summ_without_vat = 0;
foreach ($goods as $v){
	if($v['Vat'] > 0){
		$summ_with_vat += $v['Price']*$v['Qty'];
	}else{
		$summ_without_vat += $v['Price']*$v['Qty'];
	}
}
if ($order['Overcost'] > 0) $summ_with_vat += $order['Overcost'];
?>
<?if ($summ_with_vat > 0){?>
<div>
	<p>Товари та послуги з ПДВ:</p>
	<img style="margin-top:-10px;" src="<?=PROJECT_URL?>document-13-3333333333338.png" />
	<span style="margin-left:20px;vertical-align:35px;">x <?=sprintf("%01.2f",$summ_with_vat)?> грн</span>
</div>
<?}?>


<?if ($summ_without_vat > 0){?>
<div>
	<p>Товари та послуги без ПДВ:</p>
	<img style="margin-top:-10px;" src="<?=PROJECT_URL?>document-13-4444444444444.png" />
	<span style="margin-left:20px;vertical-align:35px;">x <?=sprintf("%01.2f",$summ_without_vat)?> грн</span>
</div>
<?
}

*/?>
