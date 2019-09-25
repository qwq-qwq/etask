<?php

include_once(realpath(dirname(__FILE__)."/classes/variables.php"));

Kernel::Import("classes.web.AdminPage");
//Models
Kernel::Import('classes.data.empik.AccountsTable');
Kernel::Import('classes.data.empik.OrdersTable');
Kernel::Import('classes.data.empik.SalesTable');
Kernel::Import('classes.data.empik.PaymentTypesTable');
Kernel::Import('classes.data.empik.DeliveryTypesTable');
Kernel::Import('classes.unit.moneyhelper');
Kernel::Import('classes.data.empik.GmapTable');
Kernel::Import('classes.data.etasks.InvoicesTable');
Kernel::Import('classes.data.etasks.InvoiceArticlesTable');
Kernel::Import('classes.data.empik.CommonModel');
Kernel::Import('classes.data.empik.CatalogAggregationTable');
Kernel::Import('classes.data.intranet.DepartmentsTable');
Kernel::Import("classes.data.sprut.SprutModel");
Kernel::Import('classes.data.empik.EansTable');
Kernel::Import('classes.data.empik.CatalogTreeTable');
Kernel::Import('classes.data.etasks.CollectArticlesTable');
Kernel::Import('classes.data.etasks.BillTable');
Kernel::Import("classes.data.empik.CitiesTable");
Kernel::Import('classes.data.etasks.PrintDocPattern');

class IndexPage extends AdminPage
{
	const views_dir = 'printdocuments/';//Relative to parent's templates root
	private $document_view;
	//Models
	private $AccountsTable;
	private $OrdersTable;
	private $SalesTable;
	private $PaymentTypesTable;
	private $DeliveryTypesTable;
	private $GmapTable;
	private $InvoicesTable;
	private $InvoiceArticlesTable;
	private $CommonModel;
	private $CatalogAggregationTable;
	private $DepartmentsTable;
	private $SprutModel;
	private $EansTable;
	private $CatalogTreeTable;
	private $CollectArticlesTable;

	private $PrintDocPatternTable;
	//Common data for all docs
	private $order = array();
	private $print_pattern = array();
	private $goods = array();
	private $pattern = array();
	private $task_id = 0;
	//Other
	private $Empik_tax_number = '336964926599';

	function __construct($Template) {
		parent::__construct($Template);
		$this->setResponse(new PHPResponse($this, $this->document));

		$this->AccountsTable = new AccountsTable($this->connectionEmpik);
		$this->OrdersTable = new OrdersTable($this->connectionEmpik);
		$this->SalesTable = new SalesTable($this->connectionEmpik);
		$this->PaymentTypesTable = new PaymentTypesTable($this->connectionEmpik);
		$this->DeliveryTypesTable = new DeliveryTypesTable($this->connectionEmpik);
		$this->GmapTable = new GmapTable($this->connectionEmpik);
		$this->InvoicesTable = new InvoicesTable($this->connection);
		$this->CommonModel = new CommonModel($this->connectionEmpik);
		$this->CatalogAggregationTable = new CatalogAggregationTable($this->connectionEmpik);
		$this->InvoiceArticlesTable = new InvoiceArticlesTable($this->connection);
		$this->DepartmentsTable = new DepartmentsTable($this->connectionIntranet);
		$this->SprutModel = new SprutModel(DB_SPRUT_CONNECT_PARAMS);
		$this->EansTable = new EansTable($this->connectionEmpik);
		$this->CatalogTreeTable = new CatalogTreeTable($this->connectionEmpik);
		$this->CollectArticlesTable = new CollectArticlesTable($this->connection);
		$this->billTable = new BillTable($this->connection);
		$this->citiesTable = new CitiesTable($this->connectionEmpik);
		$this->PrintDocPatternTable = new PrintDocPattern($this->connection);
	}

	function OnUpdateBillDate() {
		$bill['intBillID'] = $this->request->getString('intBillID');
		$bill = $this->billTable->Get($bill);
		if (count($bill)) {
			$bill['varTime'] = date('Y-m-d H:i:s', strtotime($this->request->getString('bill_date')));
			$this->billTable->Update($bill);
		}
		$ord = $this->request->getNumber('ord');
		$doc = $this->request->getNumber('doc');
		$task = $this->request->getNumber('task');
		$this->response->Redirect('/document.php?ord='.$ord.'&doc='.$doc.'&task='.$task);
	}

	function index () {
		parent::index();
		//Check doc type
		$doc = $this->request->getString('doc');
		if (method_exists($this, 'doc_'.$doc)) {
			$doc_type = $doc;
			$doc = 'doc_'.$doc;
			//Get order data
			$ord_id = $this->request->getNumber('ord');
			$this->order = $this->OrdersTable->Get(array('Ord_id' => $ord_id));
			//Get goods
			if (!empty($this->order)) {
				$this->task_id = $this->request->getNumber('task');
				$this->goods = $this->SalesTable->GetByFields(array('Ord_id' => $ord_id), null, false);
				$this->CorrectPrice($this->goods, $this->order['discount']);
				$this->bill = $this->billTable->GetList(array('intOrderID'=>$ord_id));
			    $this->print_pattern = $this->PrintDocPatternTable->GetList(array('doc_type' => $doc_type));
				foreach($this->print_pattern as $key => $value){
					$this->pattern[$value['fild_name']]=$value['value'];
				}
			    //Initial data
				$this->document->addValue('order', $this->order);
				$this->document->addValue('goods', $this->goods);
				$this->document->addValue('bill', $this->bill);
				$this->document->addValue('pattern', $this->pattern);
				//Prepare doc data
				$this->$doc();
			}
		}
	}

	function render() {
		parent::render();
		$this->document->addValue('print_document', $this->getTemplatesRoot().self::views_dir.$this->document_view);
	}

	function OnBarcode() {
		$code = $this->request->getString('code');
		$alg = $this->request->getString('type') == 39;
		// Define variable to prevent hacking
		define('IN_CB',true);
		// Including all required classes
		require(UNITS_PATH.'barcode/index.php');
		require(UNITS_PATH.'barcode/FColor.php');
		require(UNITS_PATH.'barcode/BarCode.php');
		require(UNITS_PATH.'barcode/FDrawing.php');
		// including the barcode technology
		if ($alg) {
			include(UNITS_PATH.'barcode/code39.barcode.php');
		} else {
			include(UNITS_PATH.'barcode/ean13.barcode.php');
		}
		// Creating some Color (arguments are R, G, B)
		$color_black = new FColor(0,0,0);
		$color_white = new FColor(255,255,255);

		if ($alg) {
			$code_generated = new code39(60,$color_black,$color_white,2,$code,4);
		} else {
			//var_dump(substr((string)$code, 0, 12));
			$code_generated = new ean13(60,$color_black,$color_white,2,substr($code,0,12),4);
		}
		/* Here is the list of the arguments
		1 - Width
		2 - Height
		3 - Filename (empty : display on screen)
		4 - Background color */
		$drawing = new FDrawing(1024,1024,'',$color_white);
		$drawing->init(); // You must call this method to initialize the image
		$drawing->add_barcode($code_generated);
		$drawing->draw_all();
		$im = $drawing->get_im();

		// Next line create the little picture, the barcode is being copied inside
		$im2 = imagecreate($code_generated->lastX,$code_generated->lastY);
		imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
		$drawing->set_im($im2);

		header('Content-Type: image/png');
		$drawing->finish(IMG_FORMAT_PNG);
		$this->terminatePage();
	}

	/**
	 * Документ "Видаткова накладна"
	 *
	 */
	private function doc_1() {
		//Payment type
		$this->document->addValue('doc_id', 1);
		$payment = $this->PaymentTypesTable->Get(array('Payment_type' => $this->order['Payment_type']));
		$this->document->addValue('paym_type', $payment);

		if ($this->order['Ord_id'] != 5673916){
            $this->document_view = 'doc_1.php';
        }else{

            if(($this->order['Delivery_type'] == 1 && $this->order['Payment_type'] == 11) || //courier with payment by card
                ($this->order['Delivery_type'] == 4  && $this->order['Delivery_type'] == 5) || //Novaya pochta or Ukrposhta those not depend from kind of payment
                ($this->order['Payment_type'] == 2)) { //for payments from bank account
                $this->document_view = 'doc_1_split_by_vat.php';
            }else{
                $this->document_view = 'doc_1.php';
            };

        };

	}
	/**
	 * Документ "Податкова накладна з ПДВ"
	 *
	 */
	private function doc_2() {
		// Указываем, что у нас тип документа C НДС
		$this->document->addValue('with_nds', 1);
		//Payment type
		$payment = $this->PaymentTypesTable->Get(array('Payment_type' => $this->order['Payment_type']));
		$this->document->addValue('paym_type', $payment);
		//Tax number
		$this->document->addValue('empik_tax_numb', $this->Empik_tax_number);
		$this->document_view = 'doc_2.php';
	}
	/**
	 * Документ "Податкова накладна без ПДВ"
	 *
	 */
	private function doc_3() {
		// Указываем, что у нас тип документа BEZ НДС
		$this->document->addValue('with_nds', 0);
		//Payment type
		$payment = $this->PaymentTypesTable->Get(array('Payment_type' => $this->order['Payment_type']));
		$this->document->addValue('paym_type', $payment);
		//Tax number
		$this->document->addValue('empik_tax_numb', $this->Empik_tax_number);
		$this->document_view = 'doc_2.php';
	}
	/**
	 * Документ "Прибутковий касовий ордер"
	 *
	 */
	private function doc_4() {
		$this->document_view = 'doc_4.php';
	}
	/**
	 * Документ "Рахунок-фактура"
	 *
	 */
	private function doc_5() {
		// Указываем, что у нас другой тип документа
		$this->document->addValue('new_doc', 1);
		$this->document->addValue('doc_id', 5);
		//Payment type
		$payment = $this->PaymentTypesTable->Get(array('Payment_type' => $this->order['Payment_type']));
		$this->document->addValue('paym_type', $payment);
		$this->document_view = 'doc_1.php';
	}
	/**
	 * Документ "Этикетка"
	 *
	 */
	private function doc_6() {
		//Payment type
		$payment = $this->PaymentTypesTable->Get(array('Payment_type' => $this->order['Payment_type']));
		$this->document->addValue('paym_type', $payment);
		//Delivery type
		$delivery = $this->DeliveryTypesTable->Get(array('Delivery_type' => $this->order['Delivery_type']));
		$this->document->addValue('deliv_type', $delivery);
		//Shop info
		$shop_inf = $this->GmapTable->GetShopAndCityBySprutCode($this->order['Asm_shop_id']);
		$this->document->addValue('shop_info', $shop_inf);
		//Check what task is it and correct id if it's packking
		$task = $this->tasksTable->Get(array('intID' => $this->task_id));
		if (!in_array($task['intType'], array(70, 75, 80, 150, 160)) && $task['intChildID'] > 0) {//It's not a transfer task. We can only suggest that it's
			$this->task_id = $task['intChildID'];			//parent packing task. So take it's child's ID as base.
		}
		//Get order's invoice
		$inv = $this->InvoicesTable->GetByFields(array('intTaskID' => $this->task_id));
		$this->document->addValue('transf_numb', $inv['intNumberInvoice']);
		//Total weight
		$weight = 0;
		foreach ($this->goods as $v) {
			$weight += $this->CommonModel->GetWeight($v['Wares_id'])*$v['Qty'];
		}
		$this->document->addValue('weight', $weight);
		//Packer's data
		$pack_task = $this->tasksTable->GetOrderPackTask($this->order['Ord_id']);
		$packer = $this->usersTable->Get(array('intUserID' => $pack_task['intExecutorID']));
		$this->document->addValue('packer', $packer);

		$city = $this->citiesTable->get(array('City_id' => $this->order['City_id']));
		$this->document->addValue('city', $city['Name_UA']);
		
		$this->document_view = 'doc_6.php';
	}

	private function doc_7() {
		$this->doc_6();
		$this->document_view = 'doc_7.php';
	}
	/**
	 * Printed document "Лист заказа"
	 *
	 */
	private function doc_8() {
		//Get order's invoice
		//Check what task is it and correct id if it's packking
//		$task = $this->tasksTable->Get(array('intID' => $this->task_id));
//		if (!in_array($task['intType'], array(70, 75, 80, 150, 160)) && $task['intChildID'] > 0) {//It's not a transfer task. We can only suggest that it's
//			$this->task_id = $task['intChildID'];			//parent packing task. So take it's child's ID as base.
//		}
		//Get order's invoice
//		$inv = $this->InvoicesTable->GetByFields(array('intTaskID' => $this->task_id));
//		$this->document->addValue('transf_numb', $inv['intNumberInvoice']);
		//Payment type
		$payment = $this->PaymentTypesTable->Get(array('Payment_type' => $this->order['Payment_type']));
		$this->document->addValue('paym_type', $payment);
		//Delivery type
		$delivery = $this->DeliveryTypesTable->Get(array('Delivery_type' => $this->order['Delivery_type']));
		$this->document->addValue('deliv_type', $delivery);
		//Manager
		$manager = $this->usersTable->Get(array('intUserID' => $this->order['Manager_id']));
		$this->document->addValue('manager', $manager);
		//Update Authors
		foreach ($this->goods as &$article) {
			$art = $this->CatalogAggregationTable->Get(array('Wares_id' => $article['Wares_id']));
			$article['Author'] = $art['Author'];
		}unset($article);
		$this->document->addValue('goods', $this->goods);

		$this->document_view = 'doc_8.php';
	}
	/**
	 * "Накладна на внутрішнє переміщення"
	 *
	 */
	private function doc_9() {
		//Check what task is it and correct id if it's not packking
		$task = $this->tasksTable->Get(array('intID' => $this->task_id));
		if (in_array($task['intType'], array(70, 75, 80))) {//It's a transfer task.
			$parent = $this->tasksTable->GetByFields(array('intChildID' => $this->task_id));
			$this->task_id = $parent['intID'];			//Find parent packing task.
		}
		//Get transfer
		$transf = $this->InvoicesTable->GetByFields(array('intTaskID' => $this->task_id));
		//Get shops names and additional parameters
		$shop = $this->DepartmentsTable->GetByFields(array('intCodeShopSprut' => $transf['intShopFrom']));
		$transf['from_shop_name'] = $shop['varValue'];
		$shop = $this->DepartmentsTable->GetByFields(array('intCodeShopSprut' => $transf['intShopTo']));
		$transf['to_shop_name'] = $shop['varValue'];
		$sprut_instance = $this->SprutModel->GetInvoice($transf['intCodeInvoice']);
		$transf['comment'] = iconv('WINDOWS-1251', 'UTF-8', $sprut_instance['DESCRIPTION']);
		$transf['Inv_date'] = $sprut_instance['DATE_WRITE_OFF_INVOICE'];
		$this->document->addValue('transf', $transf);
		//Correct goods due to transfered articles
		$transf_goods = $this->InvoiceArticlesTable->GetByFields(array('intTaskID' => $this->task_id), null, false);
		foreach ($this->goods as $key => &$article) {
			$in_trans = false;
			foreach ($transf_goods as $tran_art) {
				if ($tran_art['intArticleID'] == $article['Wares_id']) {
					//Correct quantity
					$article['Qty'] = $tran_art['intDemandQty'];
					//Get barcode
					$ean = $this->EansTable->GetByFields(array('Wares_id' => $article['Wares_id'], 'Report_unit' => 'Y'));
					$article['Ean'] = $ean['Ean'];
					$in_trans = true;
					break;
				}
			}
			if (!$in_trans) {
				unset($this->goods[$key]);
			}
		}unset($article);
		$this->document->addValue('goods', $this->goods);

		$this->document_view = 'doc_9.php';
	}

	private function doc_10() {
		//Check what task is it and correct id if it's packking
		$task = $this->tasksTable->Get(array('intID' => $this->task_id));
		if (in_array($task['intType'], array(70, 75, 80))) {//It's a transfer task.
			$parent = $this->tasksTable->GetByFields(array('intChildID' => $this->task_id));
			$this->task_id = $parent['intID'];			//Find parent packing task.
		}
		//Get transfer
		$transf = $this->InvoicesTable->GetByFields(array('intTaskID' => $this->task_id));
		//Get shops and warehouse names and additional parameters
		//Name
		$shop = $this->DepartmentsTable->GetByFields(array('intCodeShopSprut' => $transf['intShopFrom']));
		$transf['from_shop_name'] = $shop['varValue'];
		//Adress
		$shop = $this->GmapTable->GetByFields(array('sprut_code' => $transf['intShopFrom']));
		$transf['from_shop_adress'] = $shop['description_ua'];
		//Warehouse
		$wh = $this->SprutModel->GetWarehouseByShop($transf['intShopFrom']);
		$transf['from_wh_name'] = iconv('WINDOWS-1251', 'UTF-8', $wh['NAME_WAREHOUSE']);
		//Name
		$shop = $this->DepartmentsTable->GetByFields(array('intCodeShopSprut' => $transf['intShopTo']));
		$transf['to_shop_name'] = $shop['varValue'];
		//Adress
		$shop = $this->GmapTable->GetByFields(array('sprut_code' => $transf['intShopTo']));
		$transf['to_shop_adress'] = $shop['description_ua'];
		//Warehouse
		$wh = $this->SprutModel->GetWarehouseByShop($transf['intShopTo']);
		$transf['to_wh_name'] = iconv('WINDOWS-1251', 'UTF-8', $wh['NAME_WAREHOUSE']);
		//Comment
		$sprut_instance = $this->SprutModel->GetInvoice($transf['intCodeInvoice']);
		$transf['comment'] = iconv('WINDOWS-1251', 'UTF-8', $sprut_instance['DESCRIPTION']);
		$this->document->addValue('transf', $transf);

		//Packer's data
		$pack_task = $this->tasksTable->GetOrderPackTask($this->order['Ord_id']);
		$packer = $this->usersTable->Get(array('intUserID' => $pack_task['intExecutorID']));
		$this->document->addValue('packer', $packer);

		$this->document_view = 'doc_10.php';
	}

	private function doc_11() {
		//Payment type
		$payment = $this->PaymentTypesTable->Get(array('Payment_type' => $this->order['Payment_type']));
		$this->document->addValue('paym_type', $payment);
		//Delivery type
		$delivery = $this->DeliveryTypesTable->Get(array('Delivery_type' => $this->order['Delivery_type']));
		$this->document->addValue('deliv_type', $delivery);
		//Shop info
		$shop = $this->DepartmentsTable->GetByFields(array('intCodeShopSprut' => $this->order['Asm_shop_id']));
		$this->document->addValue('shop_name', $shop['varValue']);
		$wh = $this->SprutModel->GetWarehouseByShop($this->order['Asm_shop_id']);
		//Parse goods
		$waiting = array();
		$collect_goods = $this->CollectArticlesTable->GetByFields(array('intTaskID' => $this->task_id), null, false);
		foreach ($this->goods as &$article) {
			//Group data
			$group = $this->CatalogTreeTable->Get(array('Group_id' => $article['Group_id']));
			$article['Group_name'] = $group['Name_UA'];
			$group = $this->CatalogTreeTable->GetByFields(array('Menu_code' => substr($group['Menu_code'], 0, 2).'.00.00'));
			$article['Group_name'] = '<b>('.$group['Name_UA'].')</b><br />'.$article['Group_name'];
			//Check what to collect here
			$need_qty = $article['Qty'];
			$found = false;
			$article['warehouse'] = 'Очікується переміщення';
			foreach ($collect_goods as $collect_article) {
				if ($collect_article['intArticleID'] == $article['Wares_id']){
					$need_qty -= $collect_article['intDemandQty'];
					//var_dump($need_qty);
					$article['Qty'] = $collect_article['intDemandQty'];
					$article['warehouse'] = iconv('WINDOWS-1251', 'UTF-8', $wh['NAME_WAREHOUSE']);
					$found = true;
					break;
				}
			}
			if ($need_qty > 0 && $found ) {
				$tmp_art = $article;
				$tmp_art['Qty'] = $need_qty;
				$tmp_art['warehouse'] = 'Очікується переміщення';
				$waiting[] = $tmp_art;
			}
		}unset($article);
		$this->goods = array_merge($this->goods, $waiting);
		$this->document->addValue('goods', $this->goods);
		//Task data
		$task = $this->tasksTable->Get(array('intID' => $this->task_id));
		$this->document->addValue('task', $task);

		$this->document_view = 'doc_11.php';
	}

	private function doc_12() {
		//Task data
		$task = $this->tasksTable->Get(array('intID' => $this->task_id));
		$this->document->addValue('task', $task);
		$shop = $this->DepartmentsTable->Get(array('intVarID' => $task['intDepartmentID']));
		$wh = $this->SprutModel->GetWarehouseByShop($shop['intCodeShopSprut']);
		//Parse goods
		$collect_goods = $this->CollectArticlesTable->GetByFields(array('intTaskID' => $this->task_id), null, false);
		foreach ($this->goods as $k => &$article) {
			//Check what to collect here
			$found = false;
			foreach ($collect_goods as $collect_article) {
				if ($collect_article['intArticleID'] == $article['Wares_id']){
					//Group data
					$group = $this->CatalogTreeTable->Get(array('Group_id' => $article['Group_id']));
					$article['Group_name'] = $group['Name_UA'];
					$group = $this->CatalogTreeTable->GetByFields(array('Menu_code' => substr($group['Menu_code'], 0, 2).'.00.00'));
					$article['Group_name'] = '<b>('.$group['Name_UA'].')</b><br />'.$article['Group_name'];
					$article['Qty'] = $collect_article['intDemandQty'];
					$article['warehouse'] = iconv('WINDOWS-1251', 'UTF-8', $wh['NAME_WAREHOUSE']);
					$found = true;
					break;
				}
			}
			if (!$found ) {
				unset($this->goods[$k]);
			}
		}unset($article);
		$this->document->addValue('goods', $this->goods);


		$this->document_view = 'doc_12.php';
	}
}

Kernel::ProcessPage(new IndexPage("document.php"));