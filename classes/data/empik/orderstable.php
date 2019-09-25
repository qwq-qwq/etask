<?php

Kernel::Import("system.db.abstracttable");

class OrdersTable extends AbstractTable
{
	// Состояния заказа
	private $orderState = array(
		1 => 'Создан',
		20 => 'Новый',
		25 => 'Подтверждён',
		30 => 'Ожидается товар',
		40 => 'Собран',
		50 => 'Отгружен',
		55 => 'Ожидает самовывоз',
		80 => 'Аннулирован',
		90 => 'Оформлен возврат',
		100 => 'Доставлен клиенту');

	function OrdersTable(&$connection)
	{
		parent::AbstractTable($connection, DB_EMPIK_TABLE_ORDERS);

		$this->addTableField('Ord_id', DB_COLUMN_NUMERIC, true);
		$this->addTableField('Ord_date');
		$this->addTableField('Ord_changed_date');
		$this->addTableField('Pay_date');
		$this->addTableField('Shipping_date');
		$this->addTableField('Delivery_date_from');
		$this->addTableField('Delivery_date_to');
		$this->addTableField('Goods_state', DB_COLUMN_NUMERIC);
		$this->addTableField('Ord_state', DB_COLUMN_NUMERIC);
		$this->addTableField('Pay_state', DB_COLUMN_NUMERIC);
		$this->addTableField('Ord_comment');
		$this->addTableField('Adm_comment');
		$this->addTableField('User_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Manager_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Courier_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Country_id', DB_COLUMN_NUMERIC);
		$this->addTableField('City_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Delivery_type', DB_COLUMN_NUMERIC);
		$this->addTableField('Payment_type', DB_COLUMN_NUMERIC);
		$this->addTableField('Shop_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Asm_shop_id', DB_COLUMN_NUMERIC);
		$this->addTableField('Contact_name');
		$this->addTableField('Contact_phone');
		$this->addTableField('Contact_mail');
		$this->addTableField('Contact_address');
		$this->addTableField('Organization_name');
		$this->addTableField('Edrpou');
		$this->addTableField('Nds', DB_COLUMN_NUMERIC);
		$this->addTableField('Tax_number');
		$this->addTableField('Vat_certificate');
		$this->addTableField('Org_address');
		$this->addTableField('Cost');
		$this->addTableField('Overcost');
		$this->addTableField('Deliv_correction');
		$this->addTableField('discount', DB_COLUMN_NUMERIC);
		$this->addTableField('Sprut', DB_COLUMN_NUMERIC);
		$this->addTableField('Barcode_pos');
		$this->addTableField('Sprut_num', DB_COLUMN_NUMERIC);
		$this->addTableField('user_agent');
		$this->addTableField('is_preorder', DB_COLUMN_NUMERIC);
		$this->addTableField('Connected_id', DB_COLUMN_NUMERIC);
		$this->addTableField('snimi_slivki_ssn');
	}

	function &GetWithNames($data = null, $orders = null, $limitCount = null, $limitOffset = null) {
		$whereClause = "";
		if (!is_null($data)) {
			foreach ($this->columns as $column) {
				if (isset($data[$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . "=" . AbstractTable::prepareColumnValue($column, $data[$column["name"]]);
				}
				if (!empty($data['LIKE'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " LIKE(" . AbstractTable::prepareColumnValue($column, '%'.$data['LIKE'.$column["name"]].'%') . ")";
				}
				if (!empty($data['FROM'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " >= " . AbstractTable::prepareColumnValue($column, $data['FROM'.$column["name"]]);
				}
				if (!empty($data['TO'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " <= " . AbstractTable::prepareColumnValue($column, $data['TO'.$column["name"]]);
				}
				if (!empty($data['IN'.$column["name"]])) {
					if (strlen($whereClause)) $whereClause .= " AND ";
					$whereClause .= 'p.'.$column["name"] . " IN (" . $data['IN'.$column["name"]].") ";
				}
			}
		}
		if (strlen($whereClause)) $whereClause = " WHERE " . $whereClause;
		$orderClause = "";
		if (is_array($orders)) {
			$keys = array_keys($orders);
			foreach ($keys as $key) {
				if (strlen($orderClause)) {
					$orderClause .= ", ";
				}
				$orderClause = $orderClause .'p.'.$key . " ".$orders[$key];
			}
		}
		if (strlen($orderClause)) {
			$orderClause = " ORDER BY " . $orderClause;
		}
		$limitClause = "";
		if (!is_null($limitCount)) {
			if (!is_null($limitOffset)) $limitClause = $limitOffset . ", ";
			$limitClause = " LIMIT " . $limitClause . $limitCount;
		}

		$SQL = sprintf("SELECT p.*, ac.bar_code, ac.discount, ac.discount_code, ac.rejected_vip,
			co.Name_RU AS Country_name,
			c.Name_RU AS City_name,
			pa.Name_RU AS Payment_type_name,
			d.Name_RU AS Delivery_type_name,
			g.Name_RU AS Shop_name
		FROM %s as p
		 LEFT JOIN %s AS co ON co.Country_id = p.Country_id
		 LEFT JOIN %s AS c ON c.City_id = p.City_id
		 LEFT JOIN %s AS pa ON pa.Payment_type = p.Payment_type
		 LEFT JOIN %s AS d ON d.Delivery_type = p.Delivery_type
		 LEFT JOIN %s AS g ON g.id = p.Shop_id
		 LEFT JOIN %s AS ac ON ac.id = p.User_id
		  %s%s%s",
		 $this->tableName,
		 DB_EMPIK_TABLE_COUNTRIES,
		DB_EMPIK_TABLE_CITIES,
		DB_EMPIK_TABLE_PAYMENT_TYPES,
		DB_EMPIK_TABLE_DELIVERY_TYPES,
		DB_EMPIK_TABLE_GMAP,
		DB_EMPIK_TABLE_ACCOUNTS,
		$whereClause, $orderClause, $limitClause);
		@$reader = &$this->connection->ExecuteReader($SQL);
		return $reader;
	}

	function getOrderState() {
		return $this->orderState;
	}


	function GetStatesDD($ord){
		$dd = array();
		$filt = array();
		switch ($ord['Ord_state']) {
			case 1:	$filt = array(1); break;
			case 20: $filt = array(20,25,80);	break;
			case 25: $filt = array(25,80); break;
			case 30: $filt = array(30,80); if($ord['Sprut'] > 0) $filt[] = 40; break;
			case 40: $filt = array(40,50,80);	break;
			case 50: $filt = array(50,90); if($ord['Pay_state'] == 1) $filt[] = 100; break;
			case 55: $filt = array(55,90); break;
			default: $filt = array(1,20,25,30,40,50,80,90,55,100); break;
		}
		foreach ($this->orderState as $stat => $vals){
			if (in_array($stat, $filt)){
				$dd[] = array ('state'=>stat, 'name'=>$vals);
			}
		}
		return $dd;
	}

	function getState($state){
		return $this->orderState[$state];
	}

	function Update(&$data) {
		$data['Ord_changed_date'] = date('Y-m-d H:i:s');
		return parent::Update($data);
	}

  function getIds($id, $name, $preord, $phone){
    $where = array();
    $out = false;
    //$phone = preg_replace('/([ ()])/g', '', $phone);
      
    if($name) $where[] = "t1.Contact_name LIKE '%$name%'";
    if($phone) $where[] = "t1.Contact_phone LIKE '%$phone%'";
    if(!is_null($preord)) $where[] = "t1.is_preorder = $preord";
    if($id) $where[] = "t2.Wares_id= $id";
    if (count($where)) {
	    $SQL = sprintf("SELECT Ord_id FROM %s t1 JOIN %s t2 USING(Ord_id) WHERE %s",
	    	$this->tableName,
	    	DB_EMPIK_TABLE_SALES,
	    	implode(' AND ', $where)
			);
	    $res = $this->connection->ExecuteScalar($SQL, false);
	    $out = array();
	    foreach($res as $row){
	      $out[$row['Ord_id']] = $row['Ord_id'] ;
	    }
    }
    return $out;
  }
}