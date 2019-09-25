<?php
Kernel::Import('classes.unit.tasks.Task');
Kernel::Import('classes.data.empik.DiscountsTable');
Kernel::Import('classes.data.empik.BrandTable');
Kernel::Import('classes.data.empik.CatalogTreeTable');
Kernel::Import('classes.data.empik.CommonModel');
Kernel::Import('classes.data.empik.WhsTable');
Kernel::Import('classes.data.empik.CodesTable');
Kernel::Import('classes.data.etasks.CollectArticlesTable');
Kernel::Import('classes.data.etasks.PackArticlesTable');
Kernel::Import('classes.data.etasks.DeliveryArticlesTable');
Kernel::Import('classes.data.intranet.DepartmentsTable');
Kernel::Import('classes.unit.Paritet');

class CallcentreTask extends Task {

	protected $CommonModel;
	protected $CollectArticlesTable;
	protected $PackArticlesTable;
	protected $DeliveryArticlesTable;
	protected $CatalogTreeTable;

	private $TmpID = 1;

	private $NotFoundGoods = array();
	//Службы доставки, соответствуют департаментам, но так явнее.
	private $deliveryservices = array(10=>'XPOST', 20=>'Доставка Bukva');

	function __construct(&$page, $data) {
		parent::__construct($page, $data);
		$this->CommonModel = new CommonModel($this->page->getConnectionEmpik());
		$this->CollectArticlesTable = new CollectArticlesTable($this->page->getConnection());
		$this->PackArticlesTable = new PackArticlesTable($this->page->getConnection());
		$this->DeliveryArticlesTable = new DeliveryArticlesTable($this->page->getConnection());
		$this->CatalogTreeTable = new CatalogTreeTable($this->page->getConnectionEmpik());
        $this->BrandTable = new BrandTable($this->page->getConnectionEmpik());
		$this->EmpDiscountsTable = new EmpDiscountsTable($this->page->getConnectionEmpik());
		$this->WhsTable = new WhsTable($this->page->getConnectionEmpik());
		$this->CodesTable = new CodesTable($this->page->getConnectionEmpik());
		$this->DepartmentsTable = new DepartmentsTable($this->page->getConnectionIntranet());

		$this->template = "CallcentreTask.tpl";
	}

	/**
	 * Получить список товаров заказа
	 */
	protected function getOrderGoods() {
		$sales = $this->salesTable->GetByFields(array('Ord_id'=>$this->task_data['intOrderID']), null, false);
		foreach ($sales as $k => $v) {
			if (strtoupper($v['Discount_forbidden']) == 'YES') {
				$sales[$k]['discount'] = 0;
			} else {
				$sales[$k]['discount'] = max($v['discount'], $this->order_data['discount']);
			}
			$sales[$k]['PriceDiscount'] = round($v['Price'] - $v['Price']*$sales[$k]['discount']/100, 1);
			$sales[$k]['Sum'] = $sales[$k]['PriceDiscount']*$v['Qty'];
            $sales[$k]['Brand'] = $this->BrandTable->getBrandByWaresId($v['Wares_id']);
//			$est_deliv = $this->salesTable->getEstDeliv($v['Est_deliv']);
//			$sales[$k]['Est_deliv_name'] = $est_deliv['name'];
//			$sales[$k]['Est_deliv_color'] = $est_deliv['color'];
		}
		return $sales;
	}

	function OnSetExecutor() {
		$this->order_data['Manager_id'] = $this->page->getUserID();
		$this->ordersTable->Update($this->order_data);
		parent::OnSetExecutor();
	}

	function OnUnlockTask() {
		$this->task_data['intState'] = 1;
		$this->page->tasksTable->Update($this->task_data);
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}

	/**
	 * Сохранить заказ
	 *
	 */
	function OnSave() {
		$userupdate = false;
		$data['Ord_id'] = $this->task_data['intOrderID'];
		$data = $this->ordersTable->get($data);
		$data['Manager_id'] = $this->page->getUserID();
		$data['Ord_changed_date'] = date("Y-m-d H:i:s");
		$data['Adm_comment'] = $this->page->getRequest()->getString('Adm_comment');
		$codeArticle = $this->page->getRequest()->getString('codeArticle');
		
		/*  Если состояние оплаты изменилось на "Оплачено",
		 *  то высталяем дату оплаты
		 */
		$pay_state = $this->page->getRequest()->getNumber('Pay_state');
		if ($data['Pay_state'] != $pay_state && $pay_state == 1) {
			$data['Pay_date'] = date("Y-m-d H:i:s");
			$userupdate = true;
		}
		$data['Pay_state'] = $pay_state;

		$data['Delivery_date'] = $this->page->getRequest()->getString('Delivery_date');
		$data['Delivery_date_from_hour'] = $this->page->getRequest()->getString('Delivery_date_from_hour');
		$data['Delivery_date_to_hour'] = $this->page->getRequest()->getString('Delivery_date_to_hour');
		$data['Delivery_date_to_minutes'] = $this->page->getRequest()->getString('Delivery_date_to_minutes');
		$data['Delivery_date_from_minutes'] = $this->page->getRequest()->getString('Delivery_date_from_minutes');

		$dd = explode('.', $data['Delivery_date']);
		$data['Delivery_date_from'] = date('Y-m-d H:i:s', mktime($data['Delivery_date_from_hour'],$data['Delivery_date_from_minutes'],0,$dd[1],$dd[0],$dd[2]));
		$data['Delivery_date_to'] = date('Y-m-d H:i:s', mktime($data['Delivery_date_to_hour'],$data['Delivery_date_to_minutes'],0,$dd[1],$dd[0],$dd[2]));

		/* Если заказ имеет состояние "Не оплачен"
		 */
		if ($data['Pay_state'] != 1) {
			$data['Country_id'] = $this->page->getRequest()->getNumber('Country_id');
			$data['City_id'] = $this->page->getRequest()->getNumber('City_id');
			$Delivery_type = $data['Delivery_type'] = $this->page->getRequest()->getNumber('Delivery_type');

			$data['Payment_type'] = $this->page->getRequest()->getNumber('Payment_type');
			$shop = $this->page->getRequest()->getNumber('Shop_id');
			$whs = $this->page->getRequest()->getNumber('intWHS');
			$data['Shop_id'] = $Delivery_type==3?$shop:$whs;
			$deliv_price = $this->page->getRequest()->getString('DeliveryPrice');
			if ($data['Overcost'] != $deliv_price) {
				$data['Deliv_correction'] = $data['Overcost'] - $deliv_price;
			}
			$data['Overcost'] = $deliv_price;
			$data['User_id'] = $this->page->getRequest()->getNumber('User_id');
			/* Обновление списка товаров
			 */
			$sales = $this->page->getRequest()->Value('Qty');
			if (is_array($sales)) {
				foreach ($sales as $Wares_id => $Qty) {
					if ( ! is_numeric($Wares_id) || ! is_numeric($Qty) ) continue;
					$where = array('Wares_id' => $Wares_id, 'Ord_id' => $this->task_data['intOrderID']);
					$product = $this->salesTable->GetByFields($where);
					if ( ! empty($product)) {
						if($codeArticle){
							$code = $this->CodesTable->getCodeByProduct($Wares_id,$codeArticle);
							if(!empty($code)){
								$product['discount'] = $code['percent'];
								$product['Code_id'] = $code['Code_id'];
							}else{
								$this->codeNotValid = true;
							}
						}
						$product['Qty'] = $Qty;
						$this->salesTable->update($product, $where);
					}
				}
			}
		}
		/* Обновляем контактные данные
		 */
		$user_id = $this->page->getRequest()->getNumber('User_id');
		if ($user_id != $data['User_id']) {
			$acc = array('id'=>$user_id);
			$acc = $this->accountsTable->get($acc);
			if ( ! empty($acc)) {
				$data['edrpou'] = $acc['edrpou'];
				$data['nds'] = $acc['nds'];
			}
			$total = 0;
		}
		$data['User_id'] = $user_id;
		$data['Barcode_pos'] = $this->page->getRequest()->getString('Barcode_pos');
		$data['Contact_name'] = $this->page->getRequest()->getString('Contact_name');
		$data['Contact_phone'] = $this->page->getRequest()->getString('Contact_phone');
		$data['Contact_mail'] = $this->page->getRequest()->getString('Contact_mail');
		$data['Contact_address'] = $this->page->getRequest()->getString('Contact_address');
		$data['Organization_name'] = $this->page->getRequest()->getString('Organization_name');
		$data['Tax_number'] = $this->page->getRequest()->getString('Tax_number');
		$data['Vat_certificate'] = $this->page->getRequest()->getString('Vat_certificate');
		$data['Org_address'] = $this->page->getRequest()->getString('Org_address');
		$this->ordersTable->update($data);
		$this->order_data = $data;
		$this->OnRecalcDiscount();
		if($Delivery_type==1){
			$task['intID'] = $this->task_data['intID'];
			$task['intDeliveryService'] = $this->page->getRequest()->getNumber('intDeliveryService');
			$this->page->tasksTable->update($task);
		}
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}

	/**
	 * Добавить товар к заказу
	 */
	function OnAddProduct() {
		$byBarcode = true;
		$Wares_id = $this->page->getRequest()->getString('productArticle');
		$amount = $this->page->getRequest()->getNumber('productAmount', 1);
		if (is_numeric($Wares_id)) {
			$product = null;
			if ($byBarcode) { // get product by barcode
				$eansTable = New EansTable($this->page->getConnectionEmpik());
				$d = array('ean'=>$Wares_id);
				$product = $eansTable->get($d);
			}
			if (empty($product)) { // get product by Article ID
				$product['Wares_id'] = $Wares_id;
			}
			$product['Ord_id'] = $this->task_data['intOrderID'];
			$product = $this->salesTable->GetByFields($product);
			if ( ! empty($product)) { // product is set in order
				$product['Qty'] += $amount;
				$this->salesTable->update($product,
						array('Ord_id' => $product['Ord_id'], 'Wares_id' => $product['Wares_id']));
				$this->OnRecalcDiscount();
			} else { // product is not set in order
				$catalog_aggregation = new CatalogAggregationTable($this->page->getConnectionEmpik());
				$product = $catalog_aggregation->getCatalog($Wares_id);
				if ( ! empty($product)) {
					$product['Wares_id'] = $Wares_id;
					$product['Ord_id'] = $this->task_data['intOrderID'];
					$product['Qty'] = $amount;
					$product['discount'] = $discount;
					$this->salesTable->insert($product, true);
					$this->OnRecalcDiscount();
				}
			}
		}
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}

	/**
	 * Удалить товар заказа
	 */
	function OnDeleteProduct() {
		$data['Wares_id'] = $this->page->getRequest()->getNumber('Wares_id');
		$data['Ord_id'] = $this->task_data['intOrderID'];
		$this->salesTable->deleteByFields($data);
		$this->OnRecalcDiscount();
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}

	/**
	 * Перерасчет скидки
	 */
	function OnRecalcDiscount() {
		if ($this->order_data['Pay_state'] == 0 && $this->order_data['Ord_state'] < 50) {//Dont change anything if order already payed or sent
			//Init defaults
			$discount['non_acc'] = 0;
			$discount['acc'] = 0;
			$discount['club'] = 0;
			$discount['client'] = 0;
			$free_delivery = false;
			$club_discount = 0;
			$all_discount = 0;
			$total_sum = 0;
			//Total sum without any discounts (this is basic number for any discount calculation)
			$raw_sum = 0;
			$order_goods = $this->getOrderGoods();
			foreach ($order_goods as $article) {
				$raw_sum += $article['Price']*$article['Qty'];
			}
			if ($this->order_data['Connected_id'] > 0) {
				$connected_order_data = $this->ordersTable->get(array('Ord_id' => $this->order_data['Connected_id']));
				if ($connected_order_data['Ord_state'] > 0) $raw_sum += $connected_order_data['Cost'];
			}
			//Get Client's accumulated summ and his current cumulative discount
			$client_sum = 0;
			if ($this->order_data['User_id'] > 0) {
				$user = $this->accountsTable->Get(array('id' => $this->order_data['User_id']));
				if (!empty($user['discount'])) {
					$discount['client'] = $user['discount'];
				}
				if (!empty($user['accumulate_sum'])) {
					$client_sum = $user['accumulate_sum'];
				}
				if (!empty($user['discount_code'])) {
					if ($user['discount_code'] == 8) $club_discount = 5;
					if (in_array($user['discount_code'], array(1,2))) {
						$all_discount = 5;
						$disc = $this->EmpDiscountsTable->getByFields(array('code_privat'=> $user['code_privat']));
						if ($disc['discount']){
							$all_discount = max($all_discount, (int)$disc['discount']);
						}
					}
				}
			}
			//Get discount settings
			$DiscountsTable = new DiscountsTable($this->page->getConnectionEmpik());
			$non_accumulatives = $DiscountsTable->GetByFields(array('accumulate' => 0), array('sum' => 'ASC'), false);
			$accumulatives = $DiscountsTable->GetByFields(array('accumulate' => 1), array('sum' => 'ASC'), false);
			//calculate non cumulative discount
			foreach ($non_accumulatives as $discount_row) {
				if ($raw_sum >= $discount_row['sum']) {
					$discount['non_acc'] = $discount_row['percent'];
					$free_delivery |= $discount_row['free_delivery_ua'];
				}
			}
			//calculate accumulative discount
			if($this->order_data['User_id'])
			foreach ($accumulatives as $discount_row) {
				if ($client_sum + $raw_sum >= $discount_row['sum']) {
					$discount['acc'] = $discount_row['percent'];
					$free_delivery |= $discount_row['free_delivery_ua'];
				}
			}
			//Calculate total sum by setting discount to every article individually
			//$CatalogTreeTable = new CatalogTreeTable($this->page->getConnectionEmpik());
			foreach ($order_goods as $article) {
				if (strtoupper($article['Discount_forbidden']) != 'YES' and $user['code_dealer'] == null) {
					if($all_discount){
						$discount['vip'] = $all_discount;
						if ($all_discount > $article['discount']) {
							$this->salesTable->update(array('discount' => $all_discount),
								array('Ord_id' => $article['Ord_id'], 'Wares_id' => $article['Wares_id']));
						}
					}elseif ($this->CatalogTreeTable->isBook($article['Group_id'])){
						$discount['club'] = $club_discount;
						if ($discount['club'] > $article['discount']) {
							$this->salesTable->update(array('discount' => $club_discount),
								array('Ord_id' => $article['Ord_id'], 'Wares_id' => $article['Wares_id']));
						}
					}
					else $discount['club'] = 0;
					if ($article['discount'] < max($discount))	$article['discount'] = max($discount);
				} else {
					$article['discount'] = 0;
				}
				$total_sum += round(round($article['Price']*(1 - $article['discount']/100),1)*$article['Qty'], 2);
			}
			//Caclulate update values
			unset($discount['club']);
			$data['Ord_id'] = $this->order_data['Ord_id'];
			if ($user['code_dealer'] == null) {
                $data['discount'] = max($discount);
            }else{
                $data['discount'] = 0;
            };
			$data['Cost'] = $total_sum;
			$price = 0;
			if ($free_delivery && $this->order_data['Country_id'] == 1) {//Ukraine free delivery
				//$data['Overcost'] = 0;
				//$data['Deliv_correction'] = 0;
			} else {
				if ($this->order_data['Delivery_type'] == 4) {//Postal delivery
					$price = $this->CalcUkrPostPrice($total_sum);
				} elseif ($this->order_data['Delivery_type'] == 1) {//Courier delivery
					$price = $this->CommonModel->GetDataTable('courier_price');
				} elseif ($this->order_data['Delivery_type'] == 5) {//NewPost delivery
					$price = $this->CommonModel->GetDataTable('new_post_price');
				}
			}
			$price += $this->order_data['Logistic_correction'];
			if ($price == 0) {
				$this->order_data['Deliv_correction'] = $data['Deliv_correction'] = 0;
			}
			//Choose what to update (correction or price)
			if ($this->order_data['Deliv_correction'] == 0) {
				$data['Overcost'] = $price;
			} else {
				$data['Deliv_correction'] = $this->order_data['Overcost'] - $price;
			}
			$this->ordersTable->update($data);
		}
	}

	private function CalcUkrPostPrice($Price){ // расчет стоимости доставки
		$W = 200;
		$quant = 0;
		foreach ($this->getOrderGoods() as $article){
			$W += $this->CommonModel->GetWeight($article['Wares_id'])*$article['Qty'];
			$quant += $article['Qty'];
		}
		$W = round(ceil($W/10)/100, 2);
		if ($this->order_data['Country_id'] == 1){//Ukraine
			/*$W = ($W*6 + 5.6)*1.2;
			$Q = $Price*0.015;
			$Q = ($Q < 2.5) ? 2.5*1.2 : $Q*1.2;
			$Summ = round($W + $Q, 2);
			$Summ = ceil($Summ);*/
	/*  $Summ = 30;  */
		$Summ = 0;

		}else{//Other country
			/*$exch_rate = $this->CommonModel->GetDataTable('USD/UAH');
			$Price = $Price/$exch_rate;
			$ret = $this->CommonModel->GetCountryDeliveryTaxes($this->order_data['Country_id']);
			$Avia_p = $ret['Avia_p'];
			$Deliv_price = $ret['Deliv_price'];
			$W = ($W*$Avia_p + $Deliv_price + 2 + 2)*1.2;
			$Q = $Price*0.005;
			$Q = ($Q < 1) ? 2.5*1.2 : $Q*1.2;
			$Summ = round(($W + $Q)*$exch_rate, 2);

			if ($quant == 1){
			    $Summ = 290;
			}else if($quant == 1 or $quant == 2){
			    $Summ = 410;
			}else if ($quant > 2){
			    $Summ = 560;
			}else{
			    $Summ = 0;
			};*/
            $Summ = 0;
		}
		return $Summ;
	}
	/**
	 * Изменить Заказ на перзаказ или наоборот
	 */
	function OnSetPreorder() {
		$data['Ord_id'] = $this->order_data['Ord_id'];
		$data['is_preorder'] = $this->page->getRequest()->getNumber('is_preorder');
		$data['is_preorder'] = ($data['is_preorder'] == 0) ? 1 : 0;
		$this->ordersTable->update($data);
		if ($data['is_preorder'] == 0) {
			$upd['intID'] = $this->task_data['intID'];
			$upd['varCreation'] = date('Y-m-d H:i:s');
			$this->page->tasksTable->update($upd);
		}
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}

	/**
	 * Сохранение состояния оплаты
	 */
	function OnSetPayState() {
		$data['Ord_id'] = $this->order_data['Ord_id'];
		$data['Pay_state'] = $this->page->getRequest()->getNumber('Pay_state');
		$user_id = $this->page->getRequest()->getNumber('User_id');
		if($user_id){
			$user['id'] = $user_id;
			$sales = $this->page->getRequest()->Value('Qty');
			if (is_array($sales)) {
				foreach ($sales as $Wares_id => $Qty) {
					if ( ! is_numeric($Wares_id) || ! is_numeric($Qty) ) continue;
					$where = array('Wares_id' => $Wares_id, 'Ord_id' => $this->task_data['intOrderID']);
					$product = $this->salesTable->GetByFields($where);
					$total += $product['Price']*$Qty;
				}
			}
			$user['accumulate_sum'] = $acc['accumulate_sum'] + ($total*($data['Pay_state']?1:-1));
			$this->accountsTable->update($user);
		}
		$this->ordersTable->update($data);
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}
	/**
	 * Building a Graph
	 *
	 */
	function OnBuildgraph() {
		$this->timer = microtime(true);
		$graph = $this->BuildGraph($this->page->getRequest()->getNumber('asm_shop_id'));
		//$this->noticeMe('graph',$graph);
		$this->page->GetDocument()->addValue('serialized_graph', base64_encode(serialize($graph)));

		$depts = $this->page->getUserAllDepartments();
		$types = $this->taskTypesTable->GetByFields(null, null, false);
		foreach ($graph as &$task) {
			foreach ($depts as $dep) {
				if ($dep['intVarID'] == $task['intDepartmentID']) {
					$task['visual_department_name'] = htmlspecialchars($dep['varValue'], ENT_QUOTES);

					break;
				}
			}
			if ($task['intDepartmentID'] == 46 && $task['intDeliveryService'] != 20){
				$task['visual_department_name'] = htmlspecialchars('Курьерская служба XPOST', ENT_QUOTES);
			}
			foreach ($types as $typ) {
				if ($typ['intID'] == $task['intType']) {
					$task['visual_type'] = htmlspecialchars($typ['varName'], ENT_QUOTES);

					break;
				}
			}
			foreach ($task['articles'] as &$article) {
				$article['varArticleName'] = htmlspecialchars($article['varArticleName'], ENT_QUOTES);
			}unset($article);
			$task['visual_start_date'] = date('d.m.Y H:i', strtotime($task['varCreation']));
			if (strtotime($parent['varEnd']) == 0) {
				$task['visual_end_date'] = date('d.m.Y H:i', strtotime($task['varCreation']) + $task['intExecutionTime']);
			} else {
				$task['visual_end_date'] = date('d.m.Y H:i', strtotime($task['varEnd']));
			}
		}unset($task);
		$this->page->GetDocument()->addValue('GRAPH', '('.json_encode($graph).')');
		$this->page->GetDocument()->addValue('graph_built', 1);
	}
	/**
	 * Task Complete
	 *
	 */
	function OnPerformed() {
		//  нельзя выполнить задачу КЦ если не заполнено время доставки для курьерского заказа
		if($this->order_data['Delivery_type']==1 && (empty($this->order_data['Delivery_date_from']) || $this->order_data['Delivery_date_from']=='0000-00-00 00:00:00')) {
			$this->page->addErrorMessage('Нельзя выполнить задачу КЦ если не заполнено время доставки для курьерского заказа');
			$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
			return false;
		}
		//Apply Graph
		$graph = unserialize(base64_decode($this->page->getRequest()->getString('serialized_graph')));
		$paritetAfterChastichnoTask = false;
		$paritetAfterChastichnoTaskArt = array();
		foreach ($graph as &$task) {
			if ($task['intID'] == 0) {//Save task data into DB
				if ($this->order_data['Delivery_type']==1 and empty($this->task_data['intDeliveryService'])) {
					$task['intDeliveryService'] = 10;
				}elseif($this->order_data['Delivery_type']==1){
					$task['intDeliveryService'] = $this->task_data['intDeliveryService'];
				}
				if($task['intDeliveryService'] == 10 and ($task['intType']==150 or $task['intType']==135)){
					$task['intDepartmentID'] = 49;
				}
				$task['intID'] = $this->page->tasksTable->insert($task);
				if ($task['intType'] == 40 && $task['intDepartmentID'] == PARITET_SHOP_ID) {
					// это перемещение из паритета, надо создать им файл
					$paritetExchange = new ParitetExchange($this->sprutModel);
					$paritetExchange->putFile($this->order_data, $task, $task['articles']);
				}
				//Update parent's intChildID and unlock task if all parents are done.
				$unlock = true;
				foreach ($graph as $parent_task) {
					if ($parent_task['tmpChildID'] == $task['tmpID']) {
						$data = array('intID' => $parent_task['intID'], 'intChildID' => $task['intID']);
						$this->page->tasksTable->update($data);
						if (in_array($parent_task['intState'], array(1,2,5))) $unlock = false;
					}
				}
				//If all parents are done - unlock
				if ($unlock) {
					$task['intState'] = 1;
					$this->page->tasksTable->update($task);
				}
				//Insert articles to all necessary tables (except invoice-articles, thus we don't know invoice ID).
				if (in_array($task['intType'], array(30, 40))) {//Collect table
					foreach ($task['articles'] as $article) {
						$article['intTaskID'] = $task['intID'];
						$this->CollectArticlesTable->insert($article);
					}
				} elseif (in_array($task['intType'], array(50, 60, 90, 100, 110, 115, 120))) {//Pack table
					foreach ($task['articles'] as $article) {
						$article['intTaskID'] = $task['intID'];
						$this->PackArticlesTable->insert($article);
					}
				} elseif (in_array($task['intType'], array(125, 130, 135, 140, 150, 160, 170, 180))) {//Delivery table
					foreach ($task['articles'] as $article) {
						$article['intTaskID'] = $task['intID'];
						$this->DeliveryArticlesTable->insert($article);
					}
				}
				if ($task['intType'] == 50 && $task['intDepartmentID'] == PARITET_SHOP_ID) {
					$paritetAfterChastichnoTask = $task['intID'];
					$paritetAfterChastichnoTaskArt = $task['articles'];
				}
			}
		}
		unset($task);
		/*
		если колцентр (type=20) создает задачу упаковка перемещения (type=50) для паритета (intDepartmentID=PARITET_SHOP_ID)
		и перед колцентом стоит частично закрытая (intState=6) задача сбора (intType=40) в паритете (intDepartmentID=PARITET_SHOP_ID)
		то создаем инвойс, упаковку закрываем, открываем следующую задачу перемещения и в нее дописываем номер инвойса (в поле varComment)
		*/
		if ($paritetAfterChastichnoTask !== false) {
			$paritetAfterChastichnoTask = $this->page->tasksTable->Get(array('intID' => $paritetAfterChastichnoTask));
			$prevTask = $this->page->tasksTable->GetByFields(array('intState' => 6, 'intType' => 40, 'intDepartmentID' => PARITET_SHOP_ID, 'intChildID' => $paritetAfterChastichnoTask['intID']));
			if (count($prevTask)) {
				$paritetAfterChastichnoTask["intState"] = 3; // упаковку закрываем
				$paritetAfterChastichnoTask["varStart"] = date('Y-m-d H:i:s');
				$paritetAfterChastichnoTask["varEnd"] = date('Y-m-d H:i:s');
				$this->page->tasksTable->Update($paritetAfterChastichnoTask);
				// разброликорать след. задачу
				$this->page->tasksTable->unlockNextTask($paritetAfterChastichnoTask['intChildID']);
				$peremeshenieTask = $this->page->tasksTable->Get(array('intID' => $paritetAfterChastichnoTask['intChildID']));

				// new scheme - не генерируем накладную перемещения, а генерируем накладную прихода  - для магазина сбора заказа http://task.miritec.com/project/3023/285255/
				$res = $this->sprutModel->query("select code_trade_hall from mz.shops_wh_vlad where code_shop = ".$this->order_data['Asm_shop_id']);
				if ($res === FALSE){
					mail('developers@bukva.ua', 'callcentre line '.__LINE__.' sql error', $sql);
					break;
				}
				$code_ward = $res[0]["CODE_TRADE_HALL"];

				$number_inv = $this->sprutModel->ora_function('MZ.GETNUMBERINVOICE', array());
				if ($number_inv === FALSE){
					mail('developers@bukva.ua', 'callcentre line '.__LINE__.' sql error', 'ora_function MZ.GETNUMBERINVOICE');
					break;
				}

				$COMMENT_TO_INVOICE = iconv('UTF-8', 'Windows-1251', 'Накладная для Интернет-магазина [EMPIKUA_ORDER_NUMBER='.$this->order_data['Ord_id'].'; ETASK_TASK_NUMBER='.$prevTask['intID'].';]');
				$sql = "declare
						code_supplier# integer:=6154441;
						code_pattern# integer:= 1499;
						code_shop# integer:=".$this->order_data['Asm_shop_id'].";
						code_warh# integer:=".$code_ward.";
						code_inv# integer:=null;
						number_inv# integer:=".$number_inv.";
						res integer;
						paycalc integer;
						begin
						select MZ.GETCODEINVOICE into code_inv# from dual;

						insert into MZ.INVOICE
						(CODE_INVOICE, CODE_SUBGROUP, CODE_SHOP, NUMBER_INVOICE, CODE_FIRM_DESTINATION, TYPE_SOURCE, CODE_SOURCE, CODE_WAREHOUSE, DATE_INVOICE, VARIETY_INVOICE, TYPE_INVOICE, TYPE_NDS, CODE_ADDITION_SIGN, STATE_INVOICE, COST_DELIVERY, CODE_CURRENCY, PERCENT_CUSTOM, TYPE_DOC_INVOICE, ID_WORKPLACE, CODE_PATTERN, NUMBER_TAX_INVOICE,DATE_TAX_INVOICE,DESCRIPTION,MUSTBE_TAX_INVOICE)
						values
						(code_inv#, 1, code_shop#, number_inv#, 6468101, 'PO', code_supplier#, code_warh#,
						trunc(sysdate), 'O', 'O', '1', -4, 'C', 0, 0, 0, '2', '911', code_pattern#,'P_".$this->order_data['Ord_id']."_".$prevTask['intID']."',trunc(sysdate),
						'".$COMMENT_TO_INVOICE."','00');\n
						";

				foreach ($paritetAfterChastichnoTaskArt as $art) {
					$current_art_row = $this->CollectArticlesTable->GetByFields(array('intTaskID' => $prevTask['intID'], 'intArticleID' => $art['intArticleID']));
					$sql .= "insert into MZ.WARES_INVOICE
							(CODE_INVOICE, CODE_WARES, QUANTITY_BY_INVOICE, CODE_UNIT_BY_INVOICE, PRICE_BY_INVOICE, PRICE_COMING, PERCENT_CUSTOM, AKCIS, ADDITION_1, ADDITION_2, ADDITION_3,vat) values(code_inv#,
							".$art['intArticleID'].",
							".$art['intDemandQty'].",
							19,
							".$current_art_row['varPrice'].",
							".$current_art_row['varPrice'].",
							0,0,0,0,0,
							(select vat/100 from mz.wares where code_wares=".$art['intArticleID']."));\n";
				}

				// далее изменяем статус накладной на "Оприходована"
				$sql .= "res:= MZ.RECEIPTSINVOICE(code_inv#, paycalc, trunc(sysdate), '2', 0, 106, 'N');\n";
				$sql .= "end;";

				$res = $this->sprutModel->query($sql);
				if ($res === FALSE){
					mail('developers@bukva.ua', 'callcentre line '.__LINE__.' sql error', $sql);
					break;
				}
				// дописываем номер инвойса
				$peremeshenieTask["varStart"] = date('Y-m-d H:i:s');
				$peremeshenieTask["intState"] = 2; // в работе чтобы не удалялась
				$peremeshenieTask["varComment"] = $number_inv;
				$this->page->tasksTable->Update($peremeshenieTask);
			}
		}

		//Set Order as confirmed
		$data = array('Ord_id' => $this->order_data['Ord_id'],
						'Ord_state' => 25,
						'Goods_state' => 0,
						'Ord_changed_date' => date('Y-m-d H:i:s'));
		$this->ordersTable->update($data);
		//Set this Task as Done
		parent::OnPerformed();
	}
	/**
	 * on Show remains
	 *
	 */
	function OnShowRemains() {
		$rem_view = array();
		foreach($this->getOrderGoods() as $article){
			$need_more = $article['Qty'];
			$remains = $this->sprutModel->GetRemains($article['Wares_id']);
			if(count($remains) == 0){
				$remains = $this->sprutModel->GetLastSuppliers($article['Wares_id']);
				foreach($remains as $wh_rem){
					$rem_view[] = array(
								'Wares_id' => $article['Wares_id'],
								'Color' => '#F00',
								'Region' => iconv('Windows-1251', 'UTF-8', date("d.m.Y", strtotime($wh_rem['DATE_INVOICE']))),
								'Warehouse_name' => iconv('Windows-1251', 'UTF-8', $wh_rem['SUPPLIER'].' '.$wh_rem['MANAGER'].' '.$wh_rem['NAME_WAREHOUSE']),
								'Qty' => 0);
				}
			}else{
				foreach($remains as $wh_rem){
					$wh_name = iconv('Windows-1251', 'UTF-8', $wh_rem['NAME_WAREHOUSE']);
					$wh_region = iconv('Windows-1251', 'UTF-8', $wh_rem['REGION']);
					$rem_view[] = array(
								'Wares_id' => $article['Wares_id'],
								'Color' => (mb_strtolower($wh_region) == 'киев')? '#0A0' : '#00F',
								'Warehouse_name' => $wh_name,
								'Region' => $wh_region,
								'Qty' => $wh_rem['QTY']);
					$need_more -= $wh_rem['QTY'];
				}
			}
			if ($need_more > 0) $this->NotFoundGoods[] = array('Wares_id' => $article['Wares_id'], 'Qty' => $need_more);
		}
		$this->page->getDocument()->addValue('show_remains', true);
		$this->page->getDocument()->addValue('remains', $rem_view);
	}
	/**
	 * User rejected VIP card. Place a mark in DB for this event to prevent user disturb in future
	 *
	 */
	function OnRejectVIPCard() {
		$data = array(
			'id' => $this->order_data['User_id'],
			'rejected_vip' => 1
		);
		$this->accountsTable->Update($data);
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}
	/**
	 * Operator cancels current order
	 * Kill all unstarted tasks and set order state to 80
	 *
	 */
	function OnCancelOrder() {
		//Delete not started task
		$this->page->tasksTable->clearNewLockedByOrderID($this->task_data['intOrderID']);
		//Search open deliveries and set them as partly performed.
		$open_tasks = $this->page->tasksTable->GetByFields(array('intOrderID' => $this->task_data['intOrderID'],
													'intState' => 2), null, false);
		foreach ($open_tasks as $s_task) {
			if (in_array($s_task['intType'], array(130, 135, 140, 150, 160, 170, 180))) {
				$s_task['intState'] = 6;
				$s_task['varEnd'] = date('Y-m-d H:i:s');
				$this->page->tasksTable->update($s_task);
			}
		}
		//Close preorder if we have one
		$err_message = 0;
		if (!empty($this->order_data['Sprut'])) {
			//Get Payment type
			$payment = $this->paymentTypesTable->Get(array('Payment_type' => $this->order_data['Payment_type']));
			if (strtolower($payment['cash']) == 'yes') {//Close SPRUT preorder
				$err_message = $this->sprutModel->CancelPreorder($this->order_data['Sprut'], $this->order_data['Asm_shop_id']);
			}
		}
		//If everythis was fine - end operation.
		if ($err_message === 0) {
			$data = array(
				'Ord_id' => $this->task_data['intOrderID'],
				'Ord_state' => 80
			);
			$this->ordersTable->Update($data);
			parent::OnPerformed();
		} else {
			$this->page->addErrorMessage($err_message);
			$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
		}
	}

	/**
	 *
	 * @see classes/unit/tasks/Task:render()
	 */
	function render() {
		echo "test";
		parent::render();
		//$this->page->GetDocument()->addValue('accounts', $this->accountsTable->getByFields(array('validated' => 1), array('surname'=>'asc'), false));
		$this->page->GetDocument()->addValue('accounts', $this->accountsTable->getByFields(array('validated' => 1, 'id' => $this->order_data['User_id']), array(), false));
		$this->page->GetDocument()->addValue('usefile', TEMPLATES_TASKS_PATH.$this->template);
		$this->page->GetDocument()->addValue('intUserID', $this->page->getUserID());
		$this->page->GetDocument()->addValue('userName', $this->page->getUserName());
		$this->page->GetDocument()->addValue('userBarCode', $this->page->getUserName());

		$this->page->GetDocument()->addValue('order', $this->order_data);

		$orderState = $this->ordersTable->GetStatesDD($this->order_data);
		$this->page->getDocument()->addValue('orderState', $orderState);
		$this->page->getDocument()->addValue('paymentState', $this->paymentState);

		$countries = $this->countriesTable->getList(null, array('Region_id'=>'asc','Name_RU'=>'asc'), null, 'GetWithRegion');
		$this->page->getDocument()->addValue('countries', $countries);

		$cities = $this->citiesTable->getCitiesByCountryID($this->order_data['Country_id']);
		$this->page->GetDocument()->addValue('cities', $cities);

		$deliverytypes = $this->deliveryTypesTable->getDeliveryTypesByCityID($this->order_data['City_id']);
		$this->page->GetDocument()->addValue('deliverytypes', $deliverytypes);
		$this->page->GetDocument()->addValue('deliveryservices', $this->deliveryservices);

		if ( ! empty($this->order_data['Delivery_type'])) {
			$paymenttypes = $this->paymentTypesTable->getPaymentTypesByDeliveryType($this->order_data['Delivery_type']);
		}elseif ( ! empty($this->order_data['City_id'])) {
			$paymenttypes = $this->paymentTypesTable->getPaymentTypesByCityID($this->order_data['City_id']);
		}elseif ( ! empty($this->order_data['Country_id'])) {
			$paymenttypes = $this->paymentTypesTable->getPaymentTypesByCountryID($this->order_data['Country_id']);
		}else {
			$paymenttypes = $this->paymentTypesTable->getList(null, array('Name_RU'=>'asc'));
		}
		$this->page->GetDocument()->addValue('paymenttypes', $paymenttypes);

		if (is_numeric($order['Courier_id'])) {
			$data = array('id'=>$order['Courier_id']);
			$couriers = $this->accountsTable->getList($data, array('surname'=>asc));
			$this->page->GetDocument()->addValue('couriers', $couriers);
		}

		if (!empty($this->order_data['City_id'])) {
			$gmaps = $this->gmapTable->getList(array('city_id'=>$this->order_data['City_id']),array('name_ru'=>'asc'));
		} else {
			$gmaps = $this->gmapTable->getList(null, array('name_ru'=>'asc'));
		}
		if(!empty($gmaps)){
			foreach($gmaps as $key => $item){
				$gmaps[$key]['description_ru'] = addslashes(strip_tags($item['description_ru']));
			}
		}
		if (!empty($this->order_data['City_id'])) {
			$whs = $this->WhsTable->getList(array('city_id'=>$this->order_data['City_id']),array('name_ru'=>'asc'));
		} else {
			$whs = $this->WhsTable->getList(null, array('name_ru'=>'asc'));
		}
		$this->page->GetDocument()->addValue('gmaps', $gmaps);
		$this->page->GetDocument()->addValue('whs', $whs);
		//Form sales array
		$sales =  $this->getOrderGoods();
		if (count($this->NotFoundGoods) > 0) {
			$this->page->GetDocument()->addValue('done_allowed', false);
			foreach ($sales as &$article) {
				foreach ($this->NotFoundGoods as $miss_article) {
					if ($miss_article['Wares_id'] == $article['Wares_id']) {
						$article['Name'] .= ' (Не хватает '.$miss_article['Qty'].' шт.)';
						$article['style'] = 'color:red;';
					}
				}
			}unset($article);
		} elseif(count($sales) > 0) {
			$this->page->GetDocument()->addValue('done_allowed', true);
		} else {
			$this->page->GetDocument()->addValue('done_allowed', false);
		}
		if($this->codeNotValid){
			$this->page->GetDocument()->addValue('codeNotValid', true);
		}
		//Make cards notification
		$card_note = '';
		$offer_vip = false;
		$user = array();
		if ($this->order_data['User_id'] > 0) {
			$user = $this->accountsTable->Get(array('id' => $this->order_data['User_id']));
			if ($user['discount_code'] == 2) {
				$card_note .= '<div style="background-color:black;text-align:center;-moz-border-radius:2px 2px 2px 2px; vertical-align: middle; color:white; font-size: 14px; white-space: nowrap"><img src="img/ico_discount_2_30.png"> '.$user['bar_code'].'</div>';
			} 
			elseif ($user['discount_code'] == 1) {
				$card_note .= '<div style="background-color:green;text-align:center;-moz-border-radius:2px 2px 2px 2px; color:white; padding:2px 4px; white-space:nowrap; font-size: 14px"><img src="img/ico_discount_1_30.png"> '.$user['bar_code'].'</div>';
			}
			else {
				$has_book = false;
				foreach ($sales as $article) {
					if ($this->CatalogTreeTable->isBook($article['Group_id'])) {
						$has_book = true;
						break;
					}
				}
/*
				if ($has_book) {
					$card_note .= 'В данный заказ необходимо вложить клубную карту.';
				}
*/
			 }
			 //Has no vip
			 
			 if (!in_array($user['discount_code'],array(1))) {
			 	$sumPlusCurrent = $this->sprutModel->GetRetailSum($user['code_privat']);
				$sumPlusCurrent += $user['accumulate_sum'];
				if ($this->order_data['Pay_state'] != 1) $sumPlusCurrent += $this->order_data["Cost"];
				$card_note .= 'Накопления:'.$sumPlusCurrent.' Грн.';
				if ($sumPlusCurrent > 500 && $user['rejected_vip'] == 0) {
					$card_note .= ' Предложите покупателю Дисконтную карту.';
					$offer_vip = true;
				}
			 }
		}
		$this->page->GetDocument()->addValue('offer_vip', $offer_vip);
		$this->page->GetDocument()->addValue('card_note', $card_note);
		//Assembling shops list
		$asm_shops = array();
		$city_shops = array();
		//If curier delivery - filter out the shops that are not in this city
		if ($this->order_data['Delivery_type'] == 1) {
			$city_shops = $this->gmapTable->GetByFields(array('city_id' => $this->order_data['City_id']), null, false);
		}
		foreach ($this->page->getUserAllDepartments() as $dep) {
			if ($dep['intCodeShopSprut'] > 1 && $dep['intCodeShopSprut']!=PARITET_CODE_SHOP) {
				//If curier delivery - filter out the shops that are not in this city
				if ($this->order_data['Delivery_type'] == 1) {
					$not_found = true;
					foreach ($city_shops as $shop) {
						if ($shop['sprut_code'] == $dep['intCodeShopSprut']) $not_found = false;
					}
					if ($not_found) continue;
				}
				$asm_shops[] = $dep;
			}
		}
		$this->page->GetDocument()->addValue('asm_shops', $asm_shops);
		//Allow choose asm shop or not
		$show_shop = false;
		$choose_asm_shop = ($this->order_data['Delivery_type'] != 3);
		if ($choose_asm_shop) {
			$ord_tasks = $this->page->tasksTable->GetByFields(array('intOrderID' => $this->task_data['intOrderID']), null, false);
			foreach ($ord_tasks as $exist_task) {
				if (in_array($exist_task['intType'], array(30, 40)) && !in_array($exist_task['intState'], array(1,5,4))) {
					$choose_asm_shop = false;
					$show_shop = true;
				}
			}
		}
		$this->page->GetDocument()->addValue('choose_asm_shop', $choose_asm_shop);
		$this->page->GetDocument()->addValue('show_shop', $show_shop);

		$this->page->GetDocument()->addValue('sales', $sales);

		// get task comments
		$this->prepareComments();

	}

	private function BuildGraph($asm_shop = 0){
		//Init
		$Graph = array();
		$Unprocessed_articles = array();
		$choose_asm_shop = true;
		//Add order's tasks
		//$this->noticeMe('before graph',array('intOrderID' => $this->task_data['intOrderID']));
		$ord_tasks = $this->page->tasksTable->GetByFields(array('intOrderID' => $this->task_data['intOrderID']), null, false);
		//$this->noticeMe('order tasks',$ord_tasks);

		foreach ($ord_tasks as $exist_task) {
			if (!in_array($exist_task['intType'], array(10, 20))) {
				//Check if we can re-select assembling shop. (To optimize movements in case of reject for collection).
				if (in_array($exist_task['intType'], array(30, 40)) && $exist_task['intState'] != 4) {
					$choose_asm_shop = false;
					$asm_shop = 0;
				}

				$exist_task['tmpID'] = $exist_task['intID'];
				//Set no children for denied task
				if ($exist_task['intState'] == 4) {
					$exist_task['tmpChildID'] = $exist_task['intChildID'] = 0;
				} else {
					$exist_task['tmpChildID'] = $exist_task['intChildID'];
				}
				if ($exist_task['intState'] == 2) {
					$qty_field = 'intDemandQty';
				} else {
					$qty_field = 'intDoneQty';
				}
				//Fill up articles of the task
				$exist_task['articles'] = $this->page->tasksTable->GetTaskArticles($exist_task['intID'], $qty_field);
				$Graph[] = $exist_task;
				//Prevent overlaping of temporary ID's
				if ($this->TmpID <= max($exist_task['tmpID'], $exist_task['tmpChildID'])) {
					$this->TmpID = max($exist_task['tmpID'], $exist_task['tmpChildID']) + 1;
				}
			}
		}
		//$this->noticeMe('order goods',$this->getOrderGoods());
		//Choose unprocessed articles
		foreach ($this->getOrderGoods() as $article) {
			//array of all for final packing
			$all_articles[$article['Wares_id']] = array(
							'intArticleID' => $article['Wares_id'],
						 	'varArticleName' => $article['Name'],
						 	'intDemandQty' => $article['Qty']);
			//Check if collection task for this article isn't started then add it to unprocessed
			foreach ($Graph as $task) {
				if (in_array($task['intType'], array(30, 40))) {
					foreach ($task['articles'] as $done_article) {
						if ($done_article['intArticleID'] == $article['Wares_id']) {
							$article['Qty'] -= $done_article['intDemandQty'];
						}
					}
				}
			}
			if ($article['Qty'] > 0) {
				$Unprocessed_articles[] = $article;
			}
		}
		//$this->noticeMe('not packed',$Unprocessed_articles);

		//Choose assembling shop if not already done by user
		if ($asm_shop == 0) {
			if ($choose_asm_shop) {
				$asm_shop = $this->ChooseAssemblerShop($Unprocessed_articles, $Graph);
			} else {
				$asm_shop = $this->order_data['Asm_shop_id'];
			}
		} else {
			//Update assembler shop id
			$data['Ord_id'] = $this->task_data['intOrderID'];
			$data['Asm_shop_id'] = $asm_shop;
			$this->ordersTable->update($data);
		}
		//$this->noticeMe('not packed',$Unprocessed_articles);

		$asm_region = $this->sprutModel->GetRegionByShop($asm_shop);
		//$this->noticeMe('region',$asm_region);
		//Create Collect tasks
		$collect_tasks = $this->CollectArticles($Unprocessed_articles, $asm_shop, $Graph);
		//$this->noticeMe('collect tasks',$asm_region);
		$Graph = array_merge($Graph, $collect_tasks);
		//if something is not found - exit
		if (!empty($this->NotFoundGoods)) return $Graph;
		//Create packing tasks
		//$this->noticeMe('CreatePackingTasks',$asm_region);
		$this->CreatePackingTasks($Graph, $asm_region);
		//Create transfer tasks
		//$this->noticeMe('CreateTransferTasks',$asm_region);
		$this->CreateTransferTasks($Graph, $asm_region);
		//Create final packing task
		//$this->noticeMe('final_packing',$asm_region);
		foreach ($Graph as $k => $task) {
			if (in_array($task['intType'], array(90, 100, 110, 115, 120))) {
				$final_packing = $task;
				$asm_dep_id = $final_packing['intDepartmentID'];
				unset($Graph[$k]);
				break;
			}
		}
		//$this->noticeMe('final packing',$Graph);
		if (empty($final_packing)) {
			//Find assemmbling shop's departmentID
			$depts = $this->page->getUserAllDepartments();
			$asm_dep_id = 0;
			foreach ($depts as $dep) {
				if ($dep['intCodeShopSprut'] == $asm_shop) {
					$asm_dep_id = $dep['intVarID'];
					break;
				}
			}
			//Choose exact packing type
			switch ($this->order_data['Delivery_type']) {
				case 1://Courier
					$task_type = 100;
					break;
				case 2://Autolux
					$task_type = 110;
					break;
				case 3://Self delivery
					$task_type = 90;
					break;
				case 4://UkrPost
					$task_type = 120;
					break;
				case 5://NewPost
					$task_type = 115;
					if (empty($asm_dep_id)) $asm_dep_id = $this->DepartmentsTable->getDepartmentBySprut($this->CommonModel->GetSetting('UKR_POST_pack_shop')); //Магелан киев
					break;
			}

			$final_packing = $this->CreateTask($task_type, $asm_dep_id, $all_articles);
		}
		//Update childID for all parents of this task
		foreach ($Graph as &$task) {
			if (in_array($task['intType'], array(30,70,75,80))) {
				$task['tmpChildID'] = $final_packing['tmpID'];
			}
		} unset($task);
		//Create delivery task
		if ($this->order_data['Delivery_type'] != 3) {
			foreach ($Graph as $k => $task) {
				if (in_array($task['intType'], array(125, 130, 135, 140, 150, 160, 170, 180))) {
					$delivery = $task;
					unset($Graph[$k]);
					break;
				}
			}
			if (empty($delivery)) {
				switch ($this->order_data['Delivery_type']) {
					case 1://Courier
						if ($asm_region == 'Киев') {
							$task_type = 150;
							$dep_id = DEPARTMENT_CURRIER_ID;
						} else {
							$task_type = 160;
							$dep_id = $asm_dep_id;
						}
						break;
					case 2://Autolux
						if ($asm_region == 'Киев') {
							$task_type = 180;
							$dep_id = DEPARTMENT_CURRIER_ID;
						} else {
							$task_type = 170;
							$dep_id = $asm_dep_id;
						}
						break;
					case 4://UkrPost
						if ($asm_region == 'Киев') {
							$task_type = 140;
							$dep_id = DEPARTMENT_CURRIER_ID;
						} else {
							$task_type = 130;
							$dep_id = $asm_dep_id;
						}
						break;
					case 5://NewPost
						if ($asm_region == 'Киев') {
							$task_type = 135;
							$dep_id = DEPARTMENT_CURRIER_ID;
						} else {
							$task_type = 125;
							$dep_id = $asm_dep_id;
						}
						if (empty($dep_id) and in_array($task_type, array(125,135))) $dep_id = DEPARTMENT_CURRIER_ID; //Магелан киев
						break;
						
				}
				$delivery = $this->CreateTask($task_type, $dep_id, $all_articles);
			}
			$final_packing['tmpChildID'] = $delivery['tmpID'];
		}
		
		//$this->noticeMe('final transfer',$Graph);
		$Graph[] = $final_packing;
		//print_r($Graph);
		if (!empty($delivery)) $Graph[] = $delivery;
		//Calculate time estimation
		foreach ($Graph as &$task) {
			$task['intDeliveryService']=$exist_task['intDeliveryService'];
			if (empty($task['varCreation'])) {
				//find parents
				$time = array();
				foreach ($Graph as $parent) {
					if ($parent['tmpChildID'] == $task['tmpID']) {//found
						//remember end of parent task
						if (strtotime($parent['varEnd']) == 0) {//Task not finished yet
							$ts = strtotime($parent['varCreation']) + $parent['intExecutionTime'];
							if ($ts < time()) $ts = time();
						} else {//Already done
							$ts = strtotime($parent['varEnd']);
						}
						$time[] = $ts;
					}
				}
				$creation = max($time);
				if ($creation < time()) $creation = time();
				//Check if 50% cant be done unlil 21:00 and it's no earlier than 9 AM
				if (date('Hi', $creation) < 900) {
					$creation = mktime(9, 15, 00, date('m', $creation), date('d', $creation), date('Y', $creation));
				} elseif ($creation + $task['intExecutionTime']/2 > mktime(21, 0, 0, date('m', $creation), date('d', $creation), date('Y', $creation))) {
					$creation = mktime(10, 0, 0, date('m', $creation), date('d', $creation) + 1, date('Y', $creation));
				}
				$task['varCreation'] = date('Y-m-d H:i:s', $creation);
			}
		}unset($task);
		//$this->noticeMe('end',$Graph);
		return $Graph;
	}

	private function ChooseAssemblerShop($goods, $graph = array()) {
		$shop_id = 0;
		$filter = array();
		//Filter out shops already collected goods
		foreach ($graph as $task) {
			if (in_array($task['intType'], array(30, 40))) {
				$depts = $this->page->getUserAllDepartments();
				$csp_id = 0;
				foreach ($depts as $dep) {
					if ($dep['intVarID'] == $task['intDepartmentID']) {
						$csp_id = $dep['intCodeShopSprut'];
						break;
					}
				}
				if ($csp_id > 0) {
					$filter['CODE_SHOP !='][] = $csp_id;
				}
			}
		}
		if($this->order_data['Delivery_type'] == 3) {//Self-delivery
			$shop_id = $this->order_data['Shop_id'];
		} elseif ($this->order_data['Delivery_type'] == 1) {//Courier
			//Choose region (actually means CITY)
			$res = $this->citiesTable->get(array('City_id' => $this->order_data['City_id']));
			$filter['REGION'] = $res['Name_RU'];
			$shops = $this->sprutModel->ListBestMatchedShops($goods, $filter);
			if (count($shops) > 0) {
				$shop = reset($shops);
				$shop_id = $shop['Shop_id'];
			} else {//Any shop in this City
				$res = $this->gmapTable->getByFields(array('city_id' => $this->order_data['City_id']), null, false);
				if(count($res)){
					shuffle($res);
					do {
						$shop = array_shift($res);
						if ($shop['sprut_code'] > 0) $shop_id = $shop['sprut_code'];
						if(empty($res)) break;
					} while ($shop_id == 0);
				}
				if(!$shop_id) {
					$shop_id = $this->CommonModel->GetSetting('XPOST_shop');
				}
			}
		} elseif ($this->order_data['Delivery_type'] == 5) {//NewPost
			$count_goods = 0;
			foreach ($goods as $article) {
				$count_goods += $article['Qty'];
			}
			$shops = $this->sprutModel->ListBestMatchedShops($goods, $filter);
			$test = reset($shops);
			if ($test['Qty'] == $count_goods) {//Shops set found. Choose among them
				$tryit = array();
				//Try to find Kiev shop
				foreach ($shops as $shop) {
					if ($shop['Region'] == 'Киев') {
						$shop_id = $shop['Shop_id'];
						break;
					}
					if(in_array($shop['Region'],array('Одесса', 'Днепропетровск', 'Винница'))) {
						$tryit[] = $shop['Shop_id'];
					}
				}
				//No one there? Ok, than take the first.
				if($shop_id == 0 && !empty($tryit)) 
					$shop_id = reset($tryit); 
				elseif ($shop_id == 0) $shop_id = $this->CommonModel->GetSetting('UKR_POST_pack_shop');
			} else {//Search for region having max number of goods
				$tryit = array();
				//$shop_id = $this->CommonModel->GetSetting('UKR_POST_pack_shop');
				
				$region = '';
				$regions = $this->sprutModel->ListBestMatchedRegions($goods);
				//Try to find Kiev region
				foreach ($regions as $row) {
					if ($row['Region'] == 'Киев') {
						$region = $row['Region'];
						break;
					}
					if(in_array($row['Region'],array('Одесса', 'Днепропетровск', 'Винница'))) {
						$tryit[] = $row['Region'];
					}
				}
				$notkiev = false;
				//No one there? Ok, than take the first.
				if(!empty($tryit)) {
					$region = reset($tryit);
				} elseif ($region == '') {
					$tmp = reset($regions);
					$region = $tmp['Region'];
					$notkiev = true;
				}
				//Select best shop from this region
				$filter['REGION'] = $region;
				$shops = $this->sprutModel->ListBestMatchedShops($goods, $filter);
				$shop = reset($shops);
				$shop_id = $shop['Shop_id'];
				if ($notkiev)
				$shop_id = $this->CommonModel->GetSetting('UKR_POST_pack_shop');
			}
		} else { //Autolux, ukrpost
			$count_goods = 0;
			foreach ($goods as $article) {
				$count_goods += $article['Qty'];
			}
			$shops = $this->sprutModel->ListBestMatchedShops($goods, $filter);
			$test = reset($shops);
			if ($test['Qty'] == $count_goods) {//Shops set found. Choose among them
				//Try to find not Kiev shop
				foreach ($shops as $shop) {
					if ($shop['Region'] != 'Киев') {
						$shop_id = $shop['Shop_id'];
						break;
					}
				}
				//No one there? Ok, than take the first.
				if ($shop_id == 0) $shop_id = $test['Shop_id'];
			} else {//Search for region having max number of goods
				$region = '';
				$regions = $this->sprutModel->ListBestMatchedRegions($goods);
				//Try to find not Kiev region
				foreach ($regions as $row) {
					if ($row['Region'] != 'Киев') {
						$region = $row['Region'];
						break;
					}
				}
				//No one there? Ok, than take the first.
				if ($region == '') {
					$tmp = reset($regions);
					$region = $tmp['Region'];
				}
				//Select best shop from this region
				$filter['REGION'] = $region;
				$shops = $this->sprutModel->ListBestMatchedShops($goods, $filter);
				$shop = reset($shops);
				$shop_id = $shop['Shop_id'];
			}
		}

		if($shop_id == PARITET_CODE_SHOP) {
			$shop_id = PARITET_REPLACEMENT_CODE_SHOP; // «Паритет-ШоуРум» НЕ МОЖЕТ БЫТЬ ТОЧКОЙ СБОРА ТОВАРА, потому
		}
		//Update assembler shop id
		$data['Ord_id'] = $this->task_data['intOrderID'];
		$data['Asm_shop_id'] = $shop_id;
		$this->ordersTable->update($data);


		return $shop_id;
	}

	private function CollectArticles($goods, $asm_shop_id, $graph) {
		$tasks = array();
		$filter = array();
		
		//Filter out shops already collected goods
		foreach ($graph as $task) {
			if (in_array($task['intType'], array(30, 40))) {
				$depts = $this->page->getUserAllDepartments();
				$shop_id = 0;
				foreach ($depts as $dep) {
					if ($dep['intVarID'] == $task['intDepartmentID']) {
						$shop_id = $dep['intCodeShopSprut'];
						break;
					}
				}
				if ($shop_id > 0) {
					$filter['CODE_SHOP !='][] = $shop_id;
				}
			}
		}
		//Collect as many as possible in assembling shop
		if (!in_array($asm_shop_id, $filter['CODE_SHOP !='])) {
			$task = $this->CreateCollectTask($goods, $asm_shop_id, 30);
			if (count($task) > 0){
				$tasks[(int)$task['tmpID']] = $task;
				$filter['CODE_SHOP !='][] = $asm_shop_id;
			}
		}
		//Detect our region
		$region = $this->sprutModel->GetRegionByShop($asm_shop_id);
		//Assemble in other shops
		while (count($goods) > 0) {
			$shops = $this->sprutModel->ListBestMatchedShops($goods, $filter);
			//No matches? The rest articles cannot be found
			if (count($shops) == 0) {
				$this->NotFoundGoods = $goods;
				break;
			}
			$shop_id = 0;
			//Search for shop in the same region
			foreach ($shops as $shop) {
				if ($shop['Region'] == $region) {
					$shop_id = $shop['Shop_id'];
					break;
				}
			}
			if ($shop_id == 0) {//No result. Try to find not Kiev shop.
				foreach ($shops as $shop) {
					if ($shop['Region'] != 'Киев') {
						$shop_id = $shop['Shop_id'];
						break;
					}
				}
			}
			if ($shop_id == 0) {//Again nothing. Just pick the first one.
				$shop = reset($shops);
				$shop_id = $shop['Shop_id'];
			}
			$task = $this->CreateCollectTask($goods, $shop_id);
			if (count($task) > 0) {
				$tasks[(int)$task['tmpID']] = $task;
				$filter['CODE_SHOP !='][] = $shop_id;
			}
		}

		return $tasks;
	}

	private function CreateCollectTask(&$goods, $shop_id = 0, $type = 40) {
		$task = array();
		if (count($goods) > 0) {
			$res = $this->sprutModel->GetStockGoods($goods, array('CODE_SHOP' => $shop_id));
			if (count($res) > 0) {//Create collect task
				//Identyfy department
				$depts = $this->page->getUserAllDepartments();
				$dep_id = 0;
				foreach ($depts as $dep) {
					if ($dep['intCodeShopSprut'] == $shop_id) {
						$dep_id = $dep['intVarID'];
						break;
					}
				}
				//Init task
				$task = $this->CreateTask($type, $dep_id);
				$articles = array();
				foreach ($res as $found_article) {//Parse found goods
					foreach ($goods as $g_key => $need_article) {
						if ($need_article['Wares_id'] == $found_article['CODE_WARES']) {
							$articles[$need_article['Wares_id']] = array(
								'intArticleID' => $need_article['Wares_id'],
							 	'varArticleName' => $need_article['Name'],
							 	'intDemandQty' => 0
							);
							if ($found_article['QTY'] >= $need_article['Qty']) {//This position can be assembled.
								$articles[$need_article['Wares_id']]['intDemandQty'] = $need_article['Qty'];
								unset($goods[$g_key]);
							} else {//Partly assembled
								$articles[$need_article['Wares_id']]['intDemandQty'] = $found_article['QTY'];
								$goods[$g_key]['Qty'] -= $found_article['QTY'];
							}
						}
					}
				}
				$task['articles'] = $articles;
			}
		}
		return $task;
	}

	private function CreatePackingTasks(&$Graph, $asm_region) {
		$packing_tasks = array();
		foreach ($Graph as $t_key => $task) {
			if ($task['intType'] == 40) {
				//If it's denyed - don't create
				if ($task['intState'] == 4) continue;
				//Check if child for this task already exist
				if ($task['intChildID'] > 0) {
					$tmp_id = $task['intChildID'];
					foreach ($Graph as $tmp) {
						if ($tmp['intID'] == $tmp_id) continue 2;//Skip creation of packing task
					}
				}
				//Check articles list (for old tasks) and fill it if it's empty
				if (!isset($task['articles'])) {
					$task['articles'] = $this->CollectArticlesTable->GetByFields(array('intTaskID' => $task['intID']));
				}
				//Cleanup empty arts
				foreach ($task['articles'] as $k => $article) {
					if ($article['intDemandQty'] == 0) unset($task['articles'][$k]);
				}
				//Identyfy departments shop ID. Check region and choose packing type.
				$depts = $this->page->getUserAllDepartments();
				$shop_id = 0;
				foreach ($depts as $dep) {
					if ($dep['intVarID'] == $task['intDepartmentID']) {
						$shop_id = $dep['intCodeShopSprut'];
						break;
					}
				}
				$region = $this->sprutModel->GetRegionByShop($shop_id);
				if ($region == $asm_region) $task_type = 50; else $task_type = 60;
				//Create packing task
				$p_task = $this->CreateTask($task_type, $task['intDepartmentID'], $task['articles']);
				$packing_tasks[$p_task['tmpID']] = $p_task;
				$Graph[$t_key]['tmpChildID'] = $p_task['tmpID'];
			}
		}
		$Graph = array_merge($Graph, $packing_tasks);
	}

	private function CreateTransferTasks(&$Graph, $asm_region) {
		$transfer_tasks = array();
		foreach ($Graph as $t_key => $task) {
			if (in_array($task['intType'], array(50, 60))) {
				//Check if child for this task already exist
				if ($task['intChildID'] > 0) {
					$tmp_id = $task['intChildID'];
					foreach ($Graph as $tmp) {
						if ($tmp['intID'] == $tmp_id) continue 2;//Skip creation of transfer task
					}
				}
				//Check articles list (for old tasks) and fill it if it's empty
				if (!isset($task['articles'])) {
					$task['articles'] = $this->PackArticlesTable->GetByFields(array('intTaskID' => $task['intID']));
				}
				//Check region and choose transfer type. And department
				$dep_id = $task['intDepartmentID'];
				if ($task['intType'] == 50) {
					if ($asm_region == 'Киев') {
						$task_type = 70;
						$dep_id = DEPARTMENT_CURRIER_ID;
					} else {
						$task_type = 75;
					}
				} else {
					$task_type = 80;
				}
				//Create transfer task
				$t_task = $this->CreateTask($task_type, $dep_id);
				$transfer_tasks[$t_task['tmpID']] = $t_task;
				$Graph[$t_key]['tmpChildID'] = $t_task['tmpID'];
			}
		}
		$Graph = array_merge($Graph, $transfer_tasks);
	}

	private function CreateTask($type, $dep_id, $articles = array()){
		switch ($type) {
			case 10:
				$exec_time = 1800;
				break;
			case 20:
				$exec_time = 1800;
				break;
			case 30:
//				if ($dep_id==PARITET_SHOP_ID) $dep_id = PARITET_REPLACEMENT_SHOP_ID;
				$exec_time = 3600;
				break;
			case 40:
//				if ($dep_id==PARITET_SHOP_ID) $dep_id = PARITET_REPLACEMENT_SHOP_ID;
				$exec_time = 3600;
				break;
			case 50:
				$exec_time = 1800;
				break;
			case 60:
				$exec_time = 1800;
				break;
			case 70:
				$exec_time = 21600;
				break;
			case 75:
				$exec_time = 21600;
				break;
			case 80:
				$exec_time = 172800;
				break;
			case 90:
				$exec_time = 1800;
				break;
			case 100:
//				if ($dep_id==PARITET_SHOP_ID) $dep_id = PARITET_REPLACEMENT_SHOP_ID;
				$exec_time = 1800;
				break;
			case 110:
//				if ($dep_id==PARITET_SHOP_ID) $dep_id = PARITET_REPLACEMENT_SHOP_ID;
				$exec_time = 1800;
				break;
			case 115:
			case 120:
//				if ($dep_id==PARITET_SHOP_ID) $dep_id = PARITET_REPLACEMENT_SHOP_ID;
				$exec_time = 1800;
				break;
			case 125:
			case 130:
				$exec_time = 14400;
				break;
			case 135:
			case 140:
				$exec_time = 14400;
				break;
			case 150:
				$exec_time = 14400;
				break;
			case 160:
				$exec_time = 14400;
				break;
			case 170:
				$exec_time = 14400;
				break;
			case 180:
				$exec_time = 14400;
				break;
			default:
				$exec_time = 1800;
				break;
		}
		$task = array(
			'intID' => 0,
			'tmpID' => $this->TmpID++,
		 	'intChildID' => 0,
		 	'tmpChildID' => 0,
		 	'intOrderID' => $this->task_data['intOrderID'],
		 	'varCreation' => 0,
		 	'varStart' => 0,
		 	'intExecutionTime' => $exec_time,
		 	'varEnd' => 0,
		 	'intType' => $type,
		 	'intState' => 5,
		 	'intCreatorID' => $this->page->getUserID(),
		 	'intExecutorID' => 0,
		 	'intDepartmentID' => $dep_id,
		 	'varComment' => '',
		 	'articles' => $articles
		);

		return $task;
	}
	
	private function noticeMe($str,$data=array()){
		$time = microtime(true) - $this->timer;
		//if($time<2) return;
		//mail('vitaliy.korzh@miritec.com','build graph',$str.print_r($data,true));
		mail('onischenko@bukvashops.com.ua','build graph',$str.print_r($data,true));
	}
}