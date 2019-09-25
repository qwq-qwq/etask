<?php

Class CommonModel {
	private $connection;

	function __construct(&$connection) {
		$this->connection = $connection;
	}

	/*Return article's weight*/
	function GetWeight($id = 0){
		$ret = 0;
		if (is_numeric($id) && $id > 0) {
			$SQL = sprintf('Select Property_value
				From %s
				Where Wares_id = \'%s\'
				And Property_code = 66
				Limit 0, 1', DB_EMPIK_TABLE_PROPERTIES, $id);

			$res = $this->connection->ExecuteScalar($SQL);
			if (!empty($res['Property_value'])) {
				$ret = $res['Property_value'];
			} else {
				$SQL = 'Select tre.Avg_wgt as Avg_wgt
					From %s as tre, %s as cat
					Where cat.Wares_id = \'%s\'
					And tre.Group_id = cat.Group_id
					Limit 0, 1';
				$SQL = sprintf($SQL, DB_EMPIK_TABLE_CATALOG_TREE, DB_EMPIK_TABLE_CATALOG, $id);
				$res = $this->connection->ExecuteScalar($SQL);
				if (isset($res['Avg_wgt'])) $ret = $res['Avg_wgt'];
			}
		}

		return $ret;
	}

	function GetDataTable($var = '') {
		$ret = '';
		if (is_string($var) && strlen($var) > 0) {
			$SQL = 'Select Value From %s Where Variable = \'%s\'';
			$SQL = sprintf($SQL, DB_EMPIK_TABLE_DATA, $var);
			$res = $this->connection->ExecuteScalar($SQL);
			if (isset($res['Value'])) $ret = $res['Value'];
		}
		return $ret;
	}

	function GetCountryDeliveryTaxes($country_id = 0) {
		$ret = array(0, 0);
		if (is_numeric($country_id) && $country_id > 0) {
			$SQL = "Select reg.Avia_per_kg as Avia_p, reg.Deliv_price as Deliv_price
				From %s as reg, %s as cnt
				Where cnt.Country_id = '%s'
				And reg.Region_id = cnt.Region_id
				Limit 0, 1";
			$SQL = sprintf($SQL, DB_EMPIK_TABLE_REGIONS, DB_EMPIK_TABLE_COUNTRIES, $country_id);
			$res = $this->connection->ExecuteScalar($SQL);
			if (count($res) > 0) $ret = $res;
		}
		return $ret;
	}

	function GetCourierDeliveryReport() {
		$SQL = 'Select ord.Delivery_date_from,
						ord.Delivery_date_to,
						gmp.description_ru as Address_from,
						ord.Contact_address as Address_to,
						ord.Ord_date,
						tsk.varCreation as Ready_date,
						ord.Ord_id,
						ord.Cost + ord.Overcost as Summ,
						tstate.varName as Task_state,
						ord.Ord_state,
						gmp.name_ru as Asm_shop,
						tsk.intID as Task_ID,
						ttype.varName as Task_type,
						tsk.intExecutorID,
						tsk.varStart as Start_date,
						ord.Contact_name,
						ord.Contact_phone
				From '.DB_EMPIK_TABLE_ORDERS.' as ord
				Left Join '.DB_EMPIK_TABLE_GMAP.' as gmp ON
					gmp.sprut_code = ord.Asm_shop_id
					AND gmp.sprut_code > 0
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASKS.' as tsk ON
					tsk.intOrderID = ord.Ord_id
					AND tsk.intType = 150
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_STATE.' as tstate ON tstate.intID = tsk.intState
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_TYPES.' as ttype ON ttype.intID = tsk.intType
				Where ord.Delivery_type = 1
				AND ord.City_id = 1
				AND ord.Ord_state >= 20
				AND ord.Ord_state < 80
				AND ord.is_preorder = 0';
		$res = $this->connection->ExecuteScalar($SQL, false);
		return $res;
	}

	function GetAutoluxUkrPostDeliveryReport() {
		$SQL = 'Select	gmp.description_ru as Address_from,
						ord.Contact_address as Address_to,
						ord.Ord_date,
						tsk.varCreation as Ready_date,
						ord.Ord_id,
						ord.Cost + ord.Overcost as Summ,
						tstate.varName as Task_state,
						ord.Ord_state,
						gmp.name_ru as Asm_shop,
						tsk.intID as Task_ID,
						ttype.varName as Task_type,
						tsk.intExecutorID,
						ord.Contact_name,
						ord.Contact_phone
				From '.DB_EMPIK_TABLE_ORDERS.' as ord
				Left Join '.DB_EMPIK_TABLE_GMAP.' as gmp ON
					gmp.sprut_code = ord.Asm_shop_id
					AND gmp.sprut_code > 0
				Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASKS.' as tsk ON
					tsk.intOrderID = ord.Ord_id
					AND tsk.intType IN (140, 180)
					AND tsk.intState IN (1, 2, 5)
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_STATE.' as tstate ON tstate.intID = tsk.intState
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_TYPES.' as ttype ON ttype.intID = tsk.intType
				Where ord.Delivery_type IN (2, 4)
				AND ord.Ord_state >= 20
				AND ord.Ord_state < 80
				AND ord.is_preorder = 0';
		$res = $this->connection->ExecuteScalar($SQL, false);
		return $res;
	}

	function GetTransfersReport() {
		$SQL = 'Select	ord.Ord_id,
						ord.Ord_state,
						ord.Cost + ord.Overcost as Summ,
						partsk.intDepartmentID as From_dep,
						gmp.name_ru as Asm_shop,
						tsk.varCreation as Creation_date,
						tsk.varEnd as End_date,
						tsk.intExecutionTime,
						tstate.varName as Task_state,
						tsk.intID as Task_ID,
						ttype.varName as Task_type,
						tsk.intExecutorID
				From	'.DB_EMPIK_TABLE_ORDERS.' as ord
				Left Join '.DB_EMPIK_TABLE_GMAP.' as gmp ON
					gmp.sprut_code = ord.Asm_shop_id
					AND gmp.sprut_code > 0
				Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASKS.' as tsk ON
					tsk.intOrderID = ord.Ord_id
					AND tsk.intType = 70
					AND tsk.intState IN (1, 2, 5)
				Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASKS.' as partsk ON
					partsk.intChildID = tsk.intID
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_STATE.' as tstate ON tstate.intID = tsk.intState
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_TYPES.' as ttype ON ttype.intID = tsk.intType
				Where ord.Ord_state >= 20
				AND ord.Ord_state < 80';
		$res = $this->connection->ExecuteScalar($SQL, false);
		return $res;
	}
	
	public function GetNewPostDeliveryReport() {
		$SQL = 'Select	gmp.description_ru as Address_from,
						ord.Contact_address as Address_to,
						ord.Ord_date,
						tsk.varCreation as Ready_date,
						ord.Ord_id,
						ord.Cost + ord.Overcost as Summ,
						tstate.varName as Task_state,
						ord.Ord_state,
						gmp.name_ru as Asm_shop,
						tsk.intID as Task_ID,
						ttype.varName as Task_type,
						tsk.intExecutorID,
						ord.Contact_name,
						ord.Contact_phone
				From '.DB_EMPIK_TABLE_ORDERS.' as ord
				Left Join '.DB_EMPIK_TABLE_GMAP.' as gmp ON
					gmp.sprut_code = ord.Asm_shop_id
					AND gmp.sprut_code > 0
				Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASKS.' as tsk ON
					tsk.intOrderID = ord.Ord_id
					AND tsk.intType IN (125, 135)
					AND tsk.intState IN (1, 2, 5)
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_STATE.' as tstate ON tstate.intID = tsk.intState
				Left Join '.DB_ETASK_NAME.'.'.DB_TABLE_TASK_TYPES.' as ttype ON ttype.intID = tsk.intType
				Where ord.Delivery_type = 5
				AND ord.Ord_state >= 20
				AND ord.Ord_state < 80
				AND ord.is_preorder = 0';
		$res = $this->connection->ExecuteScalar($SQL, false);
		return $res;
	}
	
	function GetSetting($name=''){
		$ret = 0;
		if(empty($name)) return;
		$SQL = 'Select Value
			From %s
			Where Variable = \'%s\'
			Limit 0, 1';
		$SQL = sprintf($SQL, DB_EMPIK_TABLE_DATA, $name, $id);
		$res = $this->connection->ExecuteScalar($SQL);
		if (isset($res['Value'])) $ret = $res['Value'];
		return $ret;
	}
}