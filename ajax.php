<?php

include_once(realpath(dirname(__FILE__)."/classes/variables.php"));

Kernel::Import("classes.web.AjaxPage");
Kernel::Import("classes.data.empik.AccountsTable");
Kernel::Import("classes.data.empik.CitiesTable");
Kernel::Import("classes.data.empik.DeliveryTypesTable");
Kernel::Import("classes.data.empik.PaymentTypesTable");
Kernel::Import("classes.data.empik.GmapTable");
Kernel::Import('classes.data.empik.OrdersTable');
Kernel::Import('classes.data.empik.SalesTable');
Kernel::Import("classes.data.etasks.TasksTable");
Kernel::Import("classes.data.sprut.SprutModel");

class IndexPage extends AjaxPage
{
	function __construct($Template) {
		parent::__construct($Template);
	}

	/**
	 * Получить информацию аккаунта
	 */
	function OnGetAccount() {
		$id = $this->request->getNumber('id');
		if (!empty($id) && is_numeric($id)) {
			$accountsTable = new AccountsTable($this->connectionEmpik);
			$account = $accountsTable->get(array('id'=>$id));
			if (!empty($account)) {
				$account_json = json_encode($this->prepareAccountInfo($account));
				$this->document->addValue('output', $account_json);
			}
		}
	}

	/**
	 * кнопка "взять задачу на исполнение".
	 * Меняет статус задачи на "в работе", выставляет дату старта задачи,
	 * выставляет исполнителя (тот, кто нажал).
	 */
	function OnSetExecutor() {
		$data['intID'] = $this->request->getNumber('ID');
		if ( ! empty($data['intID'])) {
			$taskTable = new TasksTable($this->connection);
			$task = $taskTable->get($data);
			if ( ! empty($task)) {
				$task['intState'] = 2; // в работе
				$task['intExecutorID'] = $this->getUserID();
				$task['varStart'] = date("Y-m-d H:i:s");
				$taskTable->update($task);
			}
		}
	}

	/**
	 * Получить список городов страны
	 */
	function OnGetCitiesCountry() {
		$country_id = $this->request->getNumber('id');
		if (!empty($country_id)) {
			$citiesTable = new CitiesTable($this->connectionEmpik);
			$cities = $citiesTable->getCitiesByCountryID($country_id);
			$this->document->addValue('output', json_encode($cities));
		}
	}

	/**
	 * Получить список типов доставки города
	 */
	function OnGetDeliveryTypesCity() {
		$city_id = $this->request->getNumber('id');
		if ( ! empty($city_id)) {
			$deliveryTypesTable = new DeliveryTypesTable($this->connectionEmpik);
			$deliverytypes = $deliveryTypesTable->getDeliveryTypesByCityID($city_id);
			$this->document->addValue('output', json_encode($deliverytypes));
		}
	}

	/**
	 * Получить список типов оплаты для типа доставки
	 */
	function OnGetPaymentTypes() {
		$type_id = $this->request->getNumber('id');
		if ( ! empty($type_id)) {
			$paymentTypesTable = new PaymentTypesTable($this->connectionEmpik);
			$paymenttypes = $paymentTypesTable->getPaymentTypesByDeliveryType($type_id);
			$this->document->addValue('output', json_encode($paymenttypes));
		}
	}

	/**
	 * Получить магазины города
	 */
	function OnGetShopsCity() {
		$data['city_id'] = $this->request->getNumber('id');
		if ( ! empty($data['city_id'])) {
			$gmapTable = new GmapTable($this->connectionEmpik);
			$gmaps = $gmapTable->getList($data, array('name_ru'=>'asc'));
			$this->document->addValue('output', json_encode($gmaps));
		}
	}

	/**
	 * Сменить номер карты
	 * TODO
	 */
	function OnSetCard() {
		// Fatal error: Call to undefined function oci_connect()
		/*$barCode = $this->request->getString('barCode');
		$userID = $this->request->getString('UserID');
		$accountsTable = new AccountsTable($this->connectionEmpik);
		$c = $accountsTable->getCountBarcode($barCode, $userID);
		if ($c > 0) { //bar_code already has one of local account
			$this->document->addValue('output', json_encode(array('result'=>-2)));
		} else {
			$sprutModel = new SprutModel(DB_SPRUT_CONNECT_PARAMS);
			$res = $sprutModel->checkBarCode($barCode, $userID);
			print_r($res);
			$this->document->addValue('output', json_encode(array('result'=>-3)));

		}
		$sprutModel = new SprutModel(DB_SPRUT_CONNECT_PARAMS);
		$this->document->addValue('output', json_encode(array('result'=>-3)));*/
	}

	function prepareAccountInfo( & $account) {
		// Удаляем не нужную информацию
		unset($account['password']);
		return $account;
	}

	function OnGetPDFDocs() {
		$ord_id = $this->request->getNumber('intOrderID');
		$task_type = $this->request->getNumber('intTaskType');
		$OrdersTable = new OrdersTable($this->connectionEmpik);
		$docs = array();
		$SalesTable = new SalesTable($this->connectionEmpik);
		$order = $OrdersTable->Get(array('Ord_id' => $ord_id));
		//Final packing
		if (in_array($task_type, array(90, 100, 110, 115, 120))) {
			//Legal person
			if (strlen($order['Organization_name']) > 0) {
				//Find out what kind of goods do we have
				$goods = $SalesTable->GetByFields(array('Ord_id' => $ord_id), null, false);
				$goods_with_vat = false;
				$goods_no_vat = false;
				$total_price = 0;
				foreach ($goods as $article) {
					$total_price += $article['Price']*$article['Qty'];
					if ($article['Vat'] == 0) {
						$goods_no_vat = true;
					} elseif ($article['Vat'] > 0) {
						$goods_with_vat = true;
					}
				}
				/*
				if ($goods_with_vat or $total_price<=250) {
					$docs[] = array('id' => 2, 'name' => 'Податкова накладна з ПДВ');
				}
				if ($goods_no_vat) {
					$docs[] = array('id' => 3, 'name' => 'Податкова накладна без ПДВ');
				}
				 */
				if ($order['Payment_type'] == 2) {
					$docs[] = array('id' => 5, 'name' => 'Рахунок-фактура');
				}
			}
			//ALL kind of persons
			$docs[] = array('id' => 1, 'name' => 'Видаткова накладна');
			if ($order['Delivery_type'] == 1 && $order['Pay_state'] == 0) {
				$docs[] = array('id' => 4, 'name' => 'Прибутковий касовий ордер');
			}
			//UkrPost delivery?
			if ($order['Delivery_type'] == 4) {//UkrPost
				$docs[] = array('id' => 6, 'name' => 'Етикетка');
			} elseif ($order['Delivery_type'] != 1) {//Other
				$docs[] = array('id' => 7, 'name' => 'Етикетка');
			}
			$docs[] = array('id' => 8, 'name' => 'Лист замовлення');
		}
		//Transfer packing
		if (in_array($task_type, array(50, 60))) {
			$docs[] = array('id' => 9, 'name' => 'Накладна на внутрішнє переміщення');
			$docs[] = array('id' => 10, 'name' => 'Етикетка');
		}
		//Collect order
		if ($task_type == 30) {
			$docs[] = array('id' => 11, 'name' => 'Лист сбора заказа');
		}
		//Collect transfer
		if ($task_type == 40) {
			$docs[] = array('id' => 12, 'name' => 'Лист сбора перемещения');
		}
		//Transfer task
		if (in_array($task_type, array(70, 75, 80))) {
			$docs[] = array('id' => 9, 'name' => 'Накладна на внутрішнє переміщення');
		}
		// call-centre task
		if (in_array($task_type, array(10,20))) {
			if ($order['Pay_state'] == 0 && in_array($order['Payment_type'], array(2,3,5,6))) {
				$docs[] = array('id' => 5, 'name' => 'Рахунок-фактура');
			}
		}

		$this->document->addValue('output', json_encode($docs));
	}


	function render() {
		parent::render();

	}
}

Kernel::ProcessPage(new IndexPage("void.tpl"));