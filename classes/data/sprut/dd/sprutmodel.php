<?php

Kernel::Import("system.db.oci.*");

Class SprutModel extends OciDriver {

	function __construct($properties) {
		parent::__construct($properties);
	}

  	function checkBarCode($bar_code, $id = null) {
    	// check sprut if card registered
		$sql = "select pr.code_privat, pc.code_discount_card discount_code, pc.discount discount
				from mz.privat pr, mz.p_client pc
				where pr.bar_code='".$bar_code."'
				and pc.code_privat=pr.code_privat
				and pc.CODE_DISCOUNT_CARD in (2,8) and pc.sign_activity = 'Y'";
		$q = $this->query($sql);
		//print_r($q);
		$data = array();
		if (is_array($q) && count($q) > 0) {
			$data = reset($q);
		}

		if (!count($data) || empty($data['CODE_PRIVAT'])) {
			return -3;
		}
		//TODO: REWRITE CODE BELOW

//		elseif (!empty($data['DISCOUNT']) && $data['DISCOUNT_CODE'] == 2){//Update temporary discount
//			$CI->db->on_duplicate('emp_discount_vip', array('code_privat' => $data['CODE_PRIVAT'], 'discount' => $data['DISCOUNT']));
//		}
		return $data;
	}


	function GetRetailSales($code_privat) {
		$sql = "select oc.date_order,ss.name_shop,round(sum(wo.QUANTITY*wo.PRICE*(1+wo.VAT )),2) sumvat_discount
		from mz.order_client oc,mz.subgroup_shop ss,mz.wares_order wo
		where oc.CODE_CLIENT=".intval($code_privat)."
		and oc.code_shop!=79
		and oc.CODE_ADDITION_SIGN!=281
		and oc.code_order=wo.code_order
		and oc.CODE_SHOP=ss.code_shop
		group by oc.code_order,oc.date_order,ss.name_shop,oc.DISCOUNT,oc.NUMBER_ORDER
		order by oc.code_order";
		return $this->query($sql);
	}

	function GetRetailSum($code_privat) {
		$data = $this->GetRetailSales($code_privat);
		$total_sum_orders = 0;
		if (is_array($data)) {
			foreach ($data as $sale) {
				$total_sum_orders += $sale['SUMVAT_DISCOUNT'];
			}
		}
		return $total_sum_orders;
	}

	function GetStockGoods($goods = array(), $filter = array()) {
		$ids = array();
		foreach ($goods as $artcle) {
			$ids[] = $artcle['Wares_id'];
		}
		$SQL = 'SELECT *
				FROM '.DB_SPRUT_TABLE_STOCK.'
				WHERE CODE_WARES IN ('.implode(', ', $ids).')';
		//Set filter
		if (is_array($filter)) {
			foreach ($filter as $col => $val) {
				if (!is_numeric($val) && !is_array($val)) $val = iconv('UTF-8', 'WINDOWS-1251', $val);
				//Interpretation of optional condition
				if (in_array(substr($col, -2), array('!=', '<=', '>='))) {
					$condition = '';
				} else {
					$condition = '=';
				}
				if (is_array($val)) {
					//Escape values
					foreach ($val as &$esc) {
						$esc = "'$esc'";
					}unset($esc);
					//Choose condition
					if ($condition == '=') {
						$condition = 'IN';
					} else {
						$condition = 'NOT IN';
						$col = substr($col, 0, strlen($col) - 2);
					}
					$SQL .= "\nAND $col $condition (".implode(', ', $val).')';
				} else {
					$SQL .= "\nAND $col $condition '$val'";
				}
			}
		}
		//Try to optimize remains
		$SQL .= ' ORDER BY QTY DESC';
		return $this->query($SQL);
	}

	function GetRegionByShop($id = 0) {
		$ret = '';
		if (is_numeric($id)) {
			$SQL = 'Select REGION From '.DB_SPRUT_TABLE_STOCK.' Where CODE_SHOP = '.$id.' AND ROWNUM = 1';
			$res = $this->query($SQL);
			if (count($res) > 0) {
				$row = reset($res);
				$ret = iconv('WINDOWS-1251', 'UTF-8', $row['REGION']);
			}
		}

		return $ret;
	}

	function GetShopsByRegion($region) {
		$ret = array();
		if (is_string($region) && strlen($region) > 0) {
			$region = iconv('UTF-8', 'WINDOWS-1251', $region);
			$SQL = 'Select CODE_SHOP From '.DB_SPRUT_TABLE_STOCK." Where REGION = '$region' Group By CODE_SHOP";
			$res = $this->query($SQL);
			if (is_array($res)) {
				foreach ($res as $shop) {
					$ret[] = $shop['CODE_SHOP'];
				}
			}
		}
		return $ret;
	}

	function ListBestMatchedShops($goods = array(), $filter = array()) {
		$matches = $this->GetStockGoods($goods, $filter);
		//Group matched articles in matched shops? No. Already done in view.
		//Search shops with max number of searched goods
		$shops = array();
		$max_count = 0;
		foreach ($goods as $artcle) {//Parse through goods
			foreach ($matches as $stock_row) {
				if ($artcle['Wares_id'] == $stock_row['CODE_WARES']) {//match

					if (isset($shops[$stock_row['CODE_SHOP']])) {//shop alreary in list
						$shops[$stock_row['CODE_SHOP']]['Qty'] += min($artcle['Qty'], $stock_row['QTY']);
					} else {//Add to list
						$shops[$stock_row['CODE_SHOP']] = array(
							'Shop_id' => $stock_row['CODE_SHOP'],
							'Region' => iconv('WINDOWS-1251', 'UTF-8', $stock_row['REGION']),
							'Qty' => min($artcle['Qty'], $stock_row['QTY'])
						);
					}
					$max_count = max($max_count, $shops[$stock_row['CODE_SHOP']]['Qty']);
				}
			}
		}
		//Filter out shops not containing max count of articles
		foreach ($shops as $id => $shop) {
			if ($shop['Qty'] < $max_count) unset($shops[$id]);
		}

		return $shops;
	}

	function ListProds() {
		$SQL = 'Select CODE_WARES, sum(QTY) QTY, REGION
				From '.DB_SPRUT_TABLE_STOCK.'
				Group by CODE_WARES, REGION
				ORDER BY QTY DESC';
		$matches = $this->query($SQL);
		print_r($matches[0]);
	}

	function ListBestMatchedRegions($goods = array()) {
		$ids = array();
		foreach ($goods as $artcle) {
			$ids[] = $artcle['Wares_id'];
		}
		$SQL = 'Select CODE_WARES, sum(QTY) QTY, REGION
				From '.DB_SPRUT_TABLE_STOCK.'
				Where CODE_WARES IN ('.implode(', ', $ids).')
				Group by CODE_WARES, REGION
				ORDER BY QTY DESC';
		$matches = $this->query($SQL);
		$regions = array();
		$max_count = 0;
		foreach ($goods as $artcle) {//Parse throuth goods
			foreach ($matches as $stock_row) {
				if ($artcle['Wares_id'] == $stock_row['CODE_WARES']) {//match
					if (isset($regions[$stock_row['REGION']])) {//region alreary in list
						$regions[$stock_row['REGION']]['Qty'] += min($artcle['Qty'], $stock_row['QTY']);
					} else {//Add to list
						$regions[$stock_row['REGION']] = array(
							'Region' => iconv('WINDOWS-1251', 'UTF-8', $stock_row['REGION']),
							'Qty' => min($artcle['Qty'], $stock_row['QTY'])
						);
					}
					$max_count = max($max_count, $regions[$stock_row['REGION']]['Qty']);
				}
			}
		}
		//Filter out shops not containing max count of articles
		foreach ($regions as $id => $region) {
			if ($region['Qty'] < $max_count) unset($regions[$id]);
		}

		return $regions;
	}

	/**
	 * Create Invoice
	 *
	 *
	 */
	function CreateInvoice($ord, $goods, $from_shop_id, $to_shop_id, $change_state = true, $change_second_state = true){
		$ord_id = $ord['Ord_id'];
		$Barcode_pos = $ord['Barcode_pos'];
		$this->begin_tran();
		//Generate invoice related data
		$code_woi = $this->ora_function("mz.GetCodeWriteOffInvoice");
		if ($code_woi === FALSE){
			$this->rollback_tran();
			return -1;
		}
		$num_woi = $this->ora_function("mz.GetNUmberWriteOffInvoice");
		if ($num_woi === FALSE){
			$this->rollback_tran();
			return -2;
		}
		if ($change_state) {
			$comment = iconv('UTF-8', 'Windows-1251', "Для комплектации заказа bukva_ua номер: $ord_id");
		} else {
			$comment = iconv('UTF-8', 'Windows-1251', "По заказу № $ord_id ШК $Barcode_pos");
		}

		$from_wh_code = $this->ora_function("MZ.GetTradeHallFromShop", array($from_shop_id));

		$to_wh_code = $this->ora_function("MZ.GetTradeHallFromShop", array($to_shop_id));
		$to_dealer_code = $this->ora_function("MZ.GetCodeDealerFromShop", array($to_shop_id));
		$total_qty = 0;

		$sql = "insert into ".DB_SPRUT_TABLE_WRITE_OFF_INVOICE."
		  (CODE_WRITE_OFF_INVOICE, CODE_SHOP, CODE_SUBGROUP, NUMBER_WRITE_OFF_INVOICE,
		DATE_WRITE_OFF_INVOICE, CODE_ADDITION_SIGN, CODE_PATTERN,
		CODE_FIRM, CODE_WAREHOUSE, VARIETY_WRITE_OFF_INVOICE, TYPE_WRITE_OFF_INVOICE, CHANGE_STATE, TYPE_PRICE,
		TYPE_ADDRESSEE, CODE_ADDRESSEE, CODE_WAREHOUSE_ADDRESSEE, CODE_SHOP_ADDRESSEE,DESCRIPTION,CODE_DEALER)
		values ('$code_woi','$from_shop_id',1,'$num_woi',
		trunc(sysdate),-4,1869,
		6468101,'$from_wh_code','T','O','02','PD',
		'F', 6468101, '$to_wh_code', '$to_shop_id', '$comment','$to_dealer_code')";

		$res = $this->query($sql);
		if ($res === FALSE){
			$this->rollback_tran();
			return -3 . '}{'.$sql;
		}
		//Insert wares into invoice
		foreach($goods as $article){
			$total_qty += $article['intDemandQty'];
			$qty = $this->ora_function('MZ.SPR$_WRITE_OFF_INVOICE.INSWARESTOWRITEOFFINVOICE', array(
						$code_woi, $article['intArticleID'], 19, $article['intDemandQty'],
						0, -1, 0, "''", 0, "''", 0, 0, 0, 1, "'C'", 0));
			if ($qty === FALSE || $qty > 0){
				$this->rollback_tran();
				return -4 . '}{'. var_export($article, true);
			}
		}
		//Set count of boxes
		$sql = 'update '.DB_SPRUT_TABLE_WRITE_OFF_INVOICE.' set QTY_BOXES = 1 where CODE_WRITE_OFF_INVOICE = '.$code_woi;
		$res = $this->query($sql);
		if ($res === FALSE){
			$this->rollback_tran();
			return -5 . '}{'.$sql;
		}
		if ($change_state) {
			//Change invoice status
			$res = $this->ora_function('MZ.CHANGESTATEWRITEOFF', array($code_woi,0,105,-1,'NULL'));
			if ($res === FALSE){
				$this->rollback_tran();
				return -6;
			}
			if ($change_second_state) {
				$res = $this->ora_function('MZ.CHANGESTATEWRITEOFF', array($code_woi,0,-1,-1,'NULL'));
				if ($res === FALSE){
					$this->rollback_tran();
					return -7;
				}
			}
		}
		$this->commit_tran();

		return array($code_woi, $num_woi, $total_qty);
	}

	function GetInvoice($code_woi) {
		$ret = array();
		if (is_numeric($code_woi)) {
			//Type = 'T'
			$SQL = 'SELECT INV.CODE_WRITE_OFF_INVOICE, INV.CODE_SHOP, INV.CODE_SHOP_ADDRESSEE, INV.DATE_WRITE_OFF_INVOICE,
			INV.STATE_WRITE_OFF_INVOICE, INV.DESCRIPTION, \'T\' AS I_TYPE,
			(Select SUM(WARS.QUANTITY) from '.DB_SPRUT_TABLE_WARES_WRITE_OFF_INVOICE.' WARS
			Where WARS.CODE_WRITE_OFF_INVOICE = INV.CODE_WRITE_OFF_INVOICE) AS QTY,
			ADS.NAME EXT_STATE
			FROM '.DB_SPRUT_TABLE_WRITE_OFF_INVOICE.' INV,
			'.DB_SPRUT_TABLE_ADDITION_SIGN.' ADS
			WHERE INV.CODE_WRITE_OFF_INVOICE = '.$code_woi.'
			AND ADS.CODE_ADDITION_SIGN = INV.CODE_ADDITION_SIGN';
			$res = $this->query($SQL);
			if (is_array($res) && count($res) == 1) $ret = $res[0];
			//Type 'I' in future
		}
		return $ret;
	}

	function InvoiceToEndState($inv_code = 0, $upd_add_sign = true){
		if(is_numeric($inv_code) && $inv_code > 0){
			//start transactions
			$this->begin_tran();
			if ($upd_add_sign) {
				$res = $this->query('update '.DB_SPRUT_TABLE_WRITE_OFF_INVOICE.' set CODE_ADDITION_SIGN  = 42 where CODE_WRITE_OFF_INVOICE = '.$inv_code);
				if ($res === FALSE){
					$this->rollback_tran();
					return -1;
				}
			}

			$res = $this->stored_procedure('MZ.SPR$_WRITE_OFF_INVOICE.WRITEOFFINVOICETONEEDSTATE',
							array($inv_code,"trunc(sysdate)","'E'",0,0,1));
			if ($res === FALSE){
				$this->rollback_tran();
				return -2;
			}
			$this->commit_tran();

			return TRUE;
		}

		return FALSE;
	}

	function isNotExistPreorder($Ord_id) {
		$sql = 'select count(*) as YEP from mz.qk_packet where description1='.intval($Ord_id);
		$res = $this->query($sql);
		if ($res === FALSE) {
			die('ERROR!' . $sql);
			return false;
		}
		if ($res[0]['YEP'] == 0) {
			return true;
		}
		return false;
	}

	function CreatePreorder($order, $goods, $code_privat = 0) {
		$barcode = '';
		$ord_code = 0;
		$ord_num = 0;
		$err_msg = '';
		//Create preorder
		if($order['Delivery_type'] == 3){//self delivery
			$unknown_const = "'SD'";
		}else{//Courier or other delivery
			$unknown_const = "'DD'";
		}
		//Start transaction
		$this->begin_tran();
		if ($code_privat == 0) $code_privat = 5089450;
		$result = $this->ora_function('MZ.QK_QUEUE_KILL.NewPacket', array(
				array('name' => ':acBarCode', 'val' => &$barcode, 'len' => 128),
        		'uid', $order['Asm_shop_id'], "'1'","'1'",
               array('name' => ':anCodePacket', 'val' => &$ord_code, 'len' => -1, 'type' => SQLT_INT),
               array('name' => ':acErrorMessage', 'val' => &$err_msg, 'len' => 128),
               "'IS'",$code_privat,'1',$unknown_const,'NULL',$order['Ord_id'],'NULL','NULL'));
		if ($result === FALSE){
			$this->rollback_tran();
			return -1;
		}
		//Insert wares into preorder
		foreach($goods as $article){
			$result = $this->ora_function('MZ.QK_QUEUE_KILL.AddWare', array(
						$ord_code, $article['Wares_id'], $article['Qty'], 19,
						array('name' => ':acErrorMessage', 'val' => &$err_msg, 'len' => 128),
						$article['PriceDiscount']));
			if ($result === FALSE){
				$this->rollback_tran();
				return -2;
			}
		}
		//Close preorder
		$result = $this->ora_function('MZ.QK_QUEUE_KILL.ClosePacket', array($ord_code,
					array('name' => ':acErrorMessage', 'val' => &$err_msg, 'len' => 128)));
		if ($result === FALSE){
			$this->rollback_tran();
			return -3;
		}
		if($order->Delivery_type != 3){
			$result = $this->ora_function('MZ.QK_QUEUE_KILL.IncomePacket', array(
						$ord_code, $order['Asm_shop_id'], 'NULL', 'NULL',
						array('name' => ':acErrorMessage', 'val' => &$err_msg, 'len' => 128)));
			if ($result === FALSE){
				$this->rollback_tran();
				return -4;
			}
		} else {//Self-delivery  -> bring preorder to "delivered" state.
			$result = $this->query("Update mz.qk_packet Set STATE_PACKET = 7 Where CODE_PACKET = $ord_code");
			if ($result === FALSE) {
				$this->rollback_tran();
				return -5;
			}
		}
		$this->commit_tran();

		return array($ord_code, $ord_num, $barcode);
	}

	function CancelPreorder($code = 0, $shop = 0) {
		$this->begin_tran();
		$err_msg = '';
		$res = $this->ora_function('mz.qk_queue_kill.CancelPacket', array(
				$code,
				$shop,
				array('name' => ':ACERRORMESSAGE', 'val' => &$err_msg, 'len' => 2000)
		));
		if ($res !== 0) {
			$this->rollback_tran();
			return $err_msg;
		}
		$this->commit_tran();

		return 0;
	}

	function CreateOrder($order, $goods){
		//Start transaction
		$this->begin_tran();
		$ord_code = $this->ora_function('MZ.GETCODEORDER', array());
		if ($ord_code === FALSE){
			$this->rollback_tran();
			return -1;
		}
		$ord_num = $this->ora_function('MZ.GETNUMBERORDER', array());
		if ($ord_num === FALSE){
			$this->rollback_tran();
			return -2;
		}
		//Choose payment pattern
		switch ($order['Payment_type']) {
			case 2:
				$pattern_code = 1506;
				break;
			case 3:
				$pattern_code = 1857;
				break;
			case 5:
				$pattern_code = 1858;
				break;
			case 6:
				$pattern_code = 1859;
				break;
			case 9:
				$pattern_code = 1908;
				break;
			case 10:
				$pattern_code = 1973;
				break;
			
			/*case IBOX_CODE:
				$pattern_code = 1860;
				break;*/ //uncomment when IBox appears
			default:
				$this->rollback_tran();
				return -3;
		}
		$client_code = 5090472;//Hardcoded. If some time shop client companies
		//will be syncronized with sprut - make determintion of this code

		$comment = iconv('UTF-8', 'Windows-1251', 'По заказу №');
		$manager_code = $this->ora_function("MZ.GETCODEMANAGERFROMCLIENT($client_code,1,'F')", array());
		if ($manager_code === FALSE){
			$this->rollback_tran();
			return -4;
		}
		$comment = str_replace("'", "''", $comment);
		$discount = $order['discount'];
		$ord_id = $order['Ord_id'];
		$shop_id = $order['Asm_shop_id'];
		$warehouse_code = $this->ora_function("MZ.GetTradeHallFromShop", array($shop_id));
		if ($warehouse_code === FALSE){
			$this->rollback_tran();
			return -8;
		}
		$sql = 'insert into '.DB_SPRUT_TABLE_ORDER_CLIENT."
		  (CODE_ORDER, CODE_SUBGROUP, CODE_PATTERN, NUMBER_ORDER, DATE_ORDER, CODE_CLIENT, CODE_MANAGER, VARIETY_ORDER, TYPE_CLIENT,
		DISCOUNT, TYPE_PAYMENT, TYPE_ORDER, TYPE_VAT, DATE_PAYMENT, PER_PP, CODE_ADDITION_SIGN, ID_WORKPLACE, PRIORITY, PERCENT,
		DOP_PERCENT, CODE_SHOP,CODE_DEALER,DESCRIPTION)
		values  ($ord_code, 1, $pattern_code,$ord_num, trunc(sysdate),$client_code, $manager_code, 'O', 'F',
		   0, 'B', 'O','OU', trunc(sysdate),  100, 281, 911, 0, 0,0, $shop_id ,366,'$comment $ord_id')";
		//var_dump($sql);
		$res = $this->query($sql);
		if ($res === FALSE){
			$this->rollback_tran();
			return -5;
		}
		//Insert wares into order
		foreach($goods as $article){
			$can_u = $this->ora_function('MZ.CanYouGetDoIt',array("'6'", "''", 51, "'A5'", "''", "''", "''"));
			if ($can_u === FALSE){
				$this->rollback_tran();
				return -6;
			}
			$price_no_vat = 100*$article['PriceDiscount']/(100 + $article['Vat']);

			$result = $this->ora_function('MZ.SPR$_ORDER_CLIENT.InsertWaresOrder', array(
						$ord_code, $article['Wares_id'], $article['Qty'], 19,0,-1,-100,6468101,$warehouse_code,-100,1,
						$can_u,$price_no_vat));
			if ($result === FALSE || $result > 0){
				$this->rollback_tran();
				return -7;
			}
		}
		$result = $this->query("Update MZ.ORDER_CLIENT Set DESCRIPTION = '$comment $ord_id' Where CODE_ORDER = $ord_code");
		if ($result === FALSE){
			$this->rollback_tran();
			return -9;
		}

		$this->commit_tran();

		return array($ord_code, $ord_num, '');
	}

	function OrderToEndState($ord_code) {
		$this->begin_tran();
		$result = $this->query("alter session set NLS_DATE_FORMAT='DD.MM.YYYY'");
		if ($result === FALSE){
			$this->rollback_tran();
			return -1;
		}
		$result = $this->query("update mz.order_client set date_order='".date('d.m.Y')."'
								where code_order=$ord_code");
		if ($result === FALSE){
			$this->rollback_tran();
			return -2;
		}
		$result = $this->stored_procedure('MZ.SPR$_ORDER_CLIENT.TOENDSTATEORDER', array($ord_code,1,"'E'",2));
		if ($result === FALSE){
			$this->rollback_tran();
			return -3;
		}

		$this->commit_tran();

		return true;
	}

	function GetWarehouseByShop($id) {
		$ret = array();
		if (is_numeric($id)) {
			$wh_code = $this->ora_function("MZ.GetTradeHallFromShop", array($id));
			if ($wh_code !== false) {
				$SQL = 'Select REGION, CODE_WAREHOUSE, NAME_WAREHOUSE From '.DB_SPRUT_TABLE_STOCK.' Where CODE_WAREHOUSE = '.$wh_code.' AND ROWNUM = 1';
				$res = $this->query($SQL);
				if (count($res) > 0) $ret = $res[0];
			}
		}

		return $ret;
	}

	function GetRemains($art_id){
		$ret = array();
		if (is_numeric($art_id) && $art_id > 0) {
			$SQL = 'Select * from '.DB_SPRUT_TABLE_STOCK.' Where CODE_WARES = '.$art_id;
			$res = $this->query($SQL);
			if ($res !== false) $ret = $res;
		}

		return $ret;
	}

	function GetWaresIdBySupplierArticle($article) {
		$SQL = "select code_wares from mz.price_supplier where code_supplier = 6154441 and articl_supplier='".$article."'";
		$res = $this->query($SQL);
		if ($res !== false) {
			return $res[0]["CODE_WARES"];
		}
		return false;
	}

	function GetProductSupplierData($ids){
		$ret = array();
		$ids = array_keys($ids);
		if (count($ids)) {
			$SQL = 'select price_supplier, articl_supplier, code_wares from mz.price_supplier where code_supplier = 6154441 and code_wares IN ('.implode(',', $ids).')';
			$res = $this->query($SQL);
			if ($res !== false) {
				foreach ($res as $rs) {
					$ret[$rs['CODE_WARES']] = $rs;
				}
			}
		}
		return $ret;
	}

	function GetLastSuppliers($art_id){
		$ret = array();
		if (is_numeric($art_id) && $art_id > 0) {
			$res = $this->query("select code_Wares ,supplier,manager,date_invoice,NAME_WAREHOUSE from
			(select code_Wares ,name supplier,name_for_print manager,date_invoice,NAME_WAREHOUSE   from
			(
			select --+index(i)
			wi.code_Wares,f.name,i.date_invoice,pr.name_for_print,wh.NAME_WAREHOUSE ,
			row_number() over (partition by wi.code_Wares,i.code_source order by i.date_invoice desc) rown
			from mz.invoice i,mz.wares_invoice wi,mz.firms f,mz.privat pr,mz.supplier sp,mz.warehouse wh
			where i.code_invoice=wi.code_invoice
			and wi.code_wares = $art_id
			and i.TYPE_INVOICE='O'
			and i.VARIETY_INVOICE='O'
			and f.code_firm=sp.CODE_FIRM
			and sp.CODE_MANAGER=pr.CODE_PRIVAT
			and i.code_source=f.code_firm
			and i.code_source!=1
			and wh.code_warehouse=i.code_warehouse
			)
			where rown=1
			order by date_invoice desc)
			where rownum<=3
			union all
			select code_Wares,name,name_for_print,date_invoice,NAME_WAREHOUSE from (
			select wii.code_Wares,ii.code_source,f.name,ii.date_invoice,pr.name_for_print,wh.NAME_WAREHOUSE,
			row_number() over (partition by wii.code_Wares order by ii.date_invoice desc) rownumber
			from (
			select --+index(i)
			wi.code_Wares,i.code_source,row_number() over (partition by wi.code_Wares order by i.date_invoice desc) rown
			from mz.invoice i,mz.wares_invoice wi
			where i.code_invoice=wi.code_invoice
			and wi.code_wares = $art_id
			and i.TYPE_INVOICE='O'
			and i.VARIETY_INVOICE='O'
			and i.code_source!=1) qw,mz.invoice ii,mz.wares_invoice wii,mz.firms f,mz.privat pr,mz.supplier sp,mz.warehouse wh
			where rown=1
			and qw.code_source=ii.code_source
			and ii.code_invoice=wii.code_invoice
			and wii.code_Wares=qw.code_Wares
			and f.code_firm=sp.CODE_FIRM
			and sp.CODE_MANAGER=pr.CODE_PRIVAT
			and qw.code_source=f.code_firm
			and wh.code_warehouse=ii.code_warehouse)
			where rownumber in (2,3)
			order by date_invoice");
			if ($res !== false) $ret = $res;
		}

		return $ret;
	}
}