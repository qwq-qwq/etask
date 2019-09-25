<?php

Kernel::Import('classes.unit.tasks.Task');
Kernel::Import("classes.data.etasks.deliveryarticlestable");

class DeliveryTask extends Task {

	/**
	 * @var taskTypesTable
	 * @see classes/data/taskTypesTable.php
	 */
	protected $taskTypesTable;
	public $addinfo = array();
	public $final_order_state = 100; // если курьер или сотрудник - то 100, иначе 50 - отгружен

	function __construct(&$page, $data) {
		parent::__construct($page, $data);

		$this->template = 'DeliveryTask.tpl';
	}

	function OnSetExecutor() {
		$upd = array(
			'Ord_id' => $this->task_data['intOrderID'],
			'Ord_state' => 50
		);
		$this->ordersTable->Update($upd);
		parent::OnSetExecutor();
	}

	function onDoneTask() {
		// апгрейд ордера в магазине, статус
		$order = array('Ord_id'=>$this->task_data["intOrderID"], 'Ord_state'=>$this->final_order_state);
		$this->ordersTable->Update($order);

		$order = $this->ordersTable->Get(array('Ord_id' => $this->task_data['intOrderID']));

		// notify user
		if (!empty($this->mail_template)) $this->notifyUser($this->task_data["intOrderID"], $this->task_data['intID']); // notify in case of ukrpost delivery

		// Check payment type
		$payment = $this->paymentTypesTable->Get(array('Payment_type' => $order['Payment_type']));
		if (strtolower($payment['cash']) == 'no') {//Close SPRUT order
			if ($this->sprutModel->OrderToEndState($order['Sprut'])) {
				$this->OnPerformed();
			}
		} else {
			$this->OnPerformed();
		}
	}

	function notifyUser($order_id, $task_id) {
		// get order data
		/*
		$order = $this->page->getConnectionEmpik()->ExecuteScalar("select ord.Ord_id, ord.Contact_mail, ord.Contact_name, shop.sprut_code as shop_id,
		shop.name_ru as shop_ru, shop.description_ru as shop_desc_ru, cntr.Name_RU country_ru,
		city.Name_RU as city_ru, ord.Contact_address, deliv.Name_RU as delivery_ru, pay.Name_RU as payment_ru,
		ord.Cost as Cost, ord.Overcost as Overcost FROM orders as ord, gmap as shop, countries as cntr, cities as city, delivery_types as deliv,
		payment_types as pay WHERE ord.Ord_id=".$order_id." and shop.sprut_code = ord.Shop_id and cntr.Country_id = ord.Country_id
		and city.City_id = ord.City_id and deliv.Delivery_type = ord.Delivery_type and pay.Payment_type = ord.Payment_type limit 1");
		*/
		
		
		 $order = $this->page->getConnectionEmpik()->ExecuteScalar("
		 select ord.Ord_id,ord.Contact_mail, ord.Contact_name, shop.sprut_code as shop_id, shop.name_ru as shop_ru, shop.description_ru as shop_desc_ru, cntr.Name_RU country_ru, city.Name_RU as city_ru, ord.Contact_address, deliv.Name_RU as delivery_ru, pay.Name_RU as payment_ru, ord.Cost as Cost, ord.Overcost as Overcost 
		 FROM  orders as ord left join gmap as shop on shop.sprut_code = ord.Shop_id
			, countries as cntr, cities as city, delivery_types as deliv, payment_types as pay
		WHERE ord.Ord_id=".$order_id." and cntr.Country_id = ord.Country_id and city.City_id = ord.City_id and deliv.Delivery_type = ord.Delivery_type and pay.Payment_type = ord.Payment_type limit 1");
		
		
		// get goods
		$DeliveryArticlesTable = new DeliveryArticlesTable($this->page->getConnection());
		$goods = $DeliveryArticlesTable->GetList(array('intTaskID' => $task_id));
 		$productLegend = '';
 		if (is_array($goods)) {
 			foreach ($goods as $i=>$good) {
 				$productLegend .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($i+1).' '.$good['varArticleName'].' ('.$good['intDemandQty'].'шт.)<br />';
 			}
 		}
		// fill template
		$template = $this->mailTemplatesTable->GetByFields(array('mail_type' => $this->mail_template));
		$message = $template["text_ru"];
		$message = str_replace('{client_name}', $order['Contact_name'], $message);
		$message = str_replace('{order_id}', $order['Ord_id'], $message);
		$message = str_replace('{total_sum}', $order['Cost'] + $order['Overcost'], $message);
		$message = str_replace('{payment_type}', $order['payment_ru'], $message);
		$message = str_replace('{delivery_type}', $order['delivery_ru'], $message);
		$message = str_replace('{country}', $order['country_ru'], $message);
		$message = str_replace('{city}', $order['city_ru'], $message);
		$message = str_replace('{delivery_address}', $order["Contact_address"], $message);
		$message = str_replace('{product_legend}', $productLegend, $message);
		// prepare email
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//		$headers .= 'To: Kelly <kelly@example.com>' . "\r\n";
		$headers .= 'From: bukva.ua <contact@bukva.ua>' . "\r\n";
		mail($order["Contact_mail"], $template["subject_ru"], $message, $headers);
	}

	function getSenderDepartment() {
		$this->addinfo['varSender'] = $this->addinfo['varExecutor']; // by default
	}

	function render(){
		parent::render();

		$this->page->GetDocument()->addValue('usefile', TEMPLATES_TASKS_PATH.$this->template);

		$depts = $this->page->getUserAllDepartments();
		foreach ($depts as $dept) {
			if ($dept['intVarID'] == $this->task_data['intDepartmentID']) {
				$this->addinfo['varExecutor'] = $dept['varValue'];
				break;
			}
		}

		$this->getSenderDepartment();

		$this->page->GetDocument()->addValue('addinfo', $this->addinfo);

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

		$whs = $this->whsTable->getlist();
		$this->page->GetDocument()->addValue('whs', $whs);

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
		$this->page->GetDocument()->addValue('gmaps', $gmaps);

		// get task comments
		$this->prepareComments();
	}

}