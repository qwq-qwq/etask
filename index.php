<?php
ini_set('memory_limit', '1024M');
include_once(realpath(dirname(__FILE__)."/classes/variables.php"));

Kernel::Import("classes.web.AdminPage");
Kernel::Import("classes.data.etasks.TaskStateTable");
Kernel::Import("classes.data.intranet.DepartmentsTable");
Kernel::Import("classes.data.intranet.UsersTable");
Kernel::Import("classes.data.empik.OrdersTable");
Kernel::Import("classes.data.empik.CommonModel");
Kernel::Import("classes.data.empik.AccountsTable");

class IndexPage extends AdminPage {

	private $filter;
	private $sort;
	private $taskstateTable;
	private $departmentsTable;
	private $OrdersTable;
	private $accountsTable;

	private $ds; // selected departments

	function index() {
		parent::index();
		$this->response->maintemplate = "layout_index.tpl";

        $this->setPageTitle('Поиск и фильтр');
		$this->setBoldMenu('tasks');

		$this->taskstateTable = new TaskStateTable($this->connection);
		$this->departmentsTable = new DepartmentsTable($this->connectionIntranet);
		$this->OrdersTable = new OrdersTable($this->connectionEmpik);
        $this->accountsTable = new AccountsTable($this->connectionEmpik);

		$filters = $this->session->get('task-filters');
		if (is_array($filters) && count($filters)) {
			$this->filter = $filters;
		} else {
			$this->setFilters();
		}
	}

	function OnSearch() {
		$this->setFilters(); // overwrite session filters
	}

	function setFilters() {
		$this->filter = array();
		$field = $this->request->getString('intID');
		if (!empty($field)) $this->filter['intID'] = $field;
		$field = $this->request->getString('intOrderID');
		if (!empty($field)) $this->filter['intOrderID'] = $field;
		$this->filter['Warez_id'] = $this->request->getString('Warez_id');
		//if (!empty($field)) $this->filter['Warez_id'] = $field;
		$this->filter['Contact_name'] = $this->request->getString('Contact_name');
        $this->filter['Contact_phone'] = $this->request->getString('Contact_phone');
		//if (!empty($field)) $this->filter['Contact_name'] = $field;else$this->filter['Contact_name'] = "";
		$field = $this->request->getString('is_preorder',null,null);
		if ($field == '1' or $field == '0') $this->filter['is_preorder'] = $field;
		
//		file_put_contents('/tmp/etasks_filter.txt', "1. $field: ".$field."\n",FILE_APPEND);

		//if (!empty($field)) $this->filter['is_preorder'] = $field;
		// show non-processing
		$field = $this->request->getString('show_5');
		if (empty($field)) $this->filter['NOTINintState'][5] = 5;
		$field = $this->request->getString('show_3');
		if (empty($field)) $this->filter['NOTINintState'][3] = 3;
		$field = $this->request->getString('show_4');
		if (empty($field)) $this->filter['NOTINintState'][4] = 4;
		$field = $this->request->getString('show_6');
		if (empty($field)) $this->filter['NOTINintState'][6] = 6;

		// department
		$field = $this->request->getString('varDepartmentID');
		$m = $this->request->getString('menuBlock');
		if (empty($field) && $m != 'my') {
			$field = $this->request->Value('intDepartmentID');
			if (!empty($field) && is_array($field)) {
				$field = $this->arrayToString($field);
			}
		}
		$this->ds = array();
		$departmentsSelected = explode(",", $field);
		if (!empty($departmentsSelected['0'])) {
			foreach ($departmentsSelected as $k=>$v) {
				$this->ds[] = array('intVarID'=>$v);
			}
		}
		if (!empty($field) && $m != 'my') $this->filter['INintDepartmentID'] = $field;
		else {
			$d = ($this->isUserAdmin() && $m != 'my') ? $this->getUserAllDepartmentIDs() : $this->getUserDepartmentIDs();
			$this->filter['INintDepartmentID'] = implode(",", $d);
			foreach ($d as $k=>$v) {
				$this->ds[] = array('intVarID'=>$v);
			}
		}

		$field = $this->request->getString('intType');
		if (!empty($field)) $this->filter['intType'] = $field;

		$field = $this->request->getString('intState');
		if (!empty($field)){
			if($field == 10) {
				$this->filter['TOvarEnd'] = date("Y-m-d H:i:s");
				$this->filter['intState10'] = 10;
			} else {
				$this->filter['intState'] = $field;
				unset($this->filter['NOTINintState'][$field]);
			}
		}
		$field = $this->request->getString('intCreatorID');
		if (!empty($field)) $this->filter['intCreatorID'] = $field;
		$field = $this->request->getString('intExecutorID');
		if (!empty($field)) $this->filter['intExecutorID'] = $field;
		$field = $this->request->getString('varCreationFrom');
		if (!empty($field)) $this->filter['FROMvarCreation'] = date('Y-m-d H:i:s', strtotime($field));
		$field = $this->request->getString('varCreationTo');
		if (!empty($field)) $this->filter['TOvarCreation'] = date('Y-m-d 23:59:59', strtotime($field));
		$field = $this->request->getString('varEndFrom');
		if (!empty($field)) $this->filter['FROMvarEnd'] = date('Y-m-d H:i:s', strtotime($field));
		$field = $this->request->getString('varEndTo');
		if (!empty($field)) $this->filter['TOvarEnd'] = date('Y-m-d 23:59:59', strtotime($field));
	}

	function OnClearFilter() {
		$this->filter = array();
	}

	function render() {
		parent::render();
		$this->filter['sortBy'] = $this->request->getString('sortBy', null, 'intID');
		$this->filter['sortOrder'] = $this->request->getNumber('sortOrder', null, 0);
		$this->sort[$this->filter['sortBy']] = ($this->filter['sortOrder']) ? 'ASC' : 'DESC';
		$this->document->addValue('sortBy', $this->filter['sortBy']);
		$this->document->addValue('sortOrder', $this->filter['sortOrder']);
		$this->page = $this->request->getNumber('tasks_page', null, 1);

		$this->document->addValue('task_types_list', $this->tasktypesTable->getList(null,array('varName'=>'asc')));
		$state_list = $this->taskstateTable->getList(null,array('varName'=>'asc'));

		// counter state task
		$counter_state = array();
		$counter_department_state = array();

		foreach ($state_list as $k=>$v) {
			$counter_state['state'.$v['intID']] = $this->tasksTable->getCountState($v['intID'], $this->getUserID(), $this->getUserDepartmentIDs());
			$counter_department_state['state'.$v['intID']] = $this->tasksTable->getCountDepartmentState($v['intID'], $this->getUserDepartmentIDs());
			$counter_department_all_state['state'.$v['intID']] = $this->tasksTable->getCountDepartmentState($v['intID'], $this->getUserAllDepartmentIDs());
		}

		$counter_state['state10'] = $this->tasksTable->getCountOverdue($this->getUserID(), $this->getUserDepartmentIDs());
		$counter_department_state['state10'] = $this->tasksTable->getCountOverdue(null, $this->getUserDepartmentIDs());
		$counter_department_all_state['state10'] = $this->tasksTable->getCountOverdue(null, $this->getUserAllDepartmentIDs());

		$state_list[] = array('intID'=>10,'varName'=>'Просроченные');
		$this->document->addValue('task_state_list', $state_list);

		$this->document->addValue('counter_state', $counter_state);
		$this->document->addValue('counter_department_state', $counter_department_state);
		$this->document->addValue('counter_department_all_state', $counter_department_all_state);

		$this->session->set('task-filters', $this->filter); // save filters
		$this->session->set('task-sort', $this->sort); // save sorting


		$ids = $this->getIds($this->filter['Warez_id'], $this->filter['Contact_name'], $this->filter['is_preorder'], $this->filter['Contact_phone']);
//		$ids = $this->OrdersTable->getIds($this->filter['Warez_id'], $this->filter['Contact_name'],$this->filter['is_preorder']);

		if ($ids !== false) $this->filter['INintOrderID'] = implode(',',$ids);

		$tasks_list = $this->tasksTable->GetList($this->filter, $this->sort, null, 'GetWithNames', 'getSQLRows', true, $this->page, DEFAULT_ITEMSPERPAGE);
		$users_list = $this->getAllUsers();

		$department_list = $this->isUserAdmin() ? $this->getUserAllDepartments() : $this->getUserDepartments();
		$this->prepareTasks($tasks_list, $department_list, $users_list);

		$this->document->addValue('departmentsSelected', $this->ds);
		$this->document->addValue('departments_list', $department_list);
		$this->document->addValue('has_courier_access', ($this->isUserAdmin() || in_array(DEPARTMENT_CURRIER_ID, $this->getUserDepartmentIDs())));
		$this->document->addValue('users_list', $users_list);
		$this->document->addValue('tasks_list', $tasks_list);

		if (!empty($this->filter['FROMvarCreation'])) $this->filter['varCreationFrom'] = date('d.m.Y', strtotime($this->filter['FROMvarCreation']));
		if (!empty($this->filter['TOvarCreation'])) $this->filter['varCreationTo'] = date('d.m.Y', strtotime($this->filter['TOvarCreation']));
		if (!empty($this->filter['FROMvarEnd'])) $this->filter['varEndFrom'] = date('d.m.Y', strtotime($this->filter['FROMvarEnd']));
		if (!empty($this->filter['TOvarEnd'])) $this->filter['varEndTo'] = date('d.m.Y', strtotime($this->filter['TOvarEnd']));
		if (isset($this->filter['intState10'])){
			$this->filter['intState'] = 10;
		}
		$this->document->addValue('filter', $this->filter);

		if (isset($tasks_list['pager']) && isset($tasks_list['pager']['total'])) {
			$this->document->addValue('total_items', $tasks_list['pager']['total']);
		} else {
			$this->document->addValue('total_items', count($tasks_list));
		}

		$this->document->addValue('mode', $this->isUserAdmin());
		$this->document->addValue('multidepartment', $this->isUserMultiDepartament());
		$this->document->addValue('intExecutorID', $this->getUserID());
		$this->document->addValue('userdepartments', $this->arrayToString($this->getUserDepartmentIDs()));
		$this->document->addValue('useralldepartments', $this->arrayToString($this->getUserAllDepartmentIDs()));
		$this->document->addValue('menuBlock', $this->request->getString('menuBlock'));
		$this->document->addValue('menudep', $this->request->getString('menudep',null,1));
		$mydep = $this->request->getString('menumydep');
		if ($mydep == 0 && $this->isUserAdmin()) $mydep = 0;

		$this->document->addValue('menumydep', $mydep);
		$this->document->addValue('menumy', $this->request->getString('menumy',null,1));
	}

	function getIds($id, $name, $preorder, $phone) {
		return $this->OrdersTable->getIds($id, $name, $preorder, $phone);
	}

	function arrayToString($a) {
		if (is_array($a)) $a = implode(",", $a);
		return $a;
	}

	function OnPrintTasks() {
		$this->filter['sortBy'] = $this->request->getString('sortBy', null, 'intID');
		$this->filter['sortOrder'] = $this->request->getNumber('sortOrder', null, 0);
		$this->sort[$this->filter['sortBy']] = ($this->filter['sortOrder']) ? 'ASC' : 'DESC';
		$tasks_list = $this->tasksTable->GetList($this->filter, $this->sort, null, 'GetWithNames', 'getSQLRows');

		$users_list = $this->getAllUsers();
		$department_list = $this->isUserAdmin() ? $this->getUserAllDepartments() : $this->getUserDepartments();
		$this->prepareTasks($tasks_list, $department_list, $users_list);

		$content = '<table><thead><th>№ задачи</th><th>№ заказа</th><th>Статус</th><th>Департамент</th><th>Тип задачи</th><th>Дата/время создания</th><th>Дата/время начала</th><th>Исполнитель</th><th>Создатель</th><th>Дата/время окончания</th><th>До завершения</th><th>КРI</th></thead><tbody>';
		foreach ($tasks_list as $k=>$v) {
			$content .= '<tr>
			<td align="center">'.$v['intID'].'</td>
			<td align="center">'.$v['intOrderID'].'</td>
			<td align="center" nowrap>'.$v['varState'].'</td>
			<td>'.$v['varDepartment'].'</td>
			<td>'.$v['varType'].'</td>
			<td align="center">'.$v['varCreation'].'</td>
			<td align="center">'.$v['varStart'].'</td>
			<td>'.$v['varExecutor'].'</td>
			<td>'.$v['varCreator'].'</td>
			<td align="center">'.$v['varEnd'].'</td>
			<td>'.$v['varTimeLeft'].'</td>
			<td>';
			if (in_array($v['intState'], array(3,4,6))) $content.=$v['intKPI'].'%';
			$content .= '</td></tr>';
		}
		$content .= '</tbody></table>';

		$fileName ='tasks_'.date('YmdHi').'.xls';

		$this->Export2excel($fileName, $content);
	}

	function OnExportCourierOrders() {
		$model = new CommonModel($this->connectionEmpik);
		$list = $model->GetCourierDeliveryReport();
		$names = $this->getAllUsersFio();
		usort($list, array('IndexPage','DateCMP'));
		$content = '<table><thead>
						<th>Номер заказа</th>
						<th>Статус</th>
						<th>На сумму</th>
						<th>Доставка с</th>
						<th>Доставить по</th>
						<th>Магазин сбора</th>
						<th>Адрес магазина</th>
						<th>Адрес доставки</th>
						<th>ФИО</th>
						<th>Телефон</th>
						<th>Заказа создан</th>
						<th>Планируемое окончание упаковки</th>
						<th>Статус задачи доставки</th>
						<th>Задача взята в работу</th>
						<th>Номер задачи доставки</th>
						<th>Тип задачи доставки</th>
						<th>Пользователь выполняет</th>
					</thead><tbody>';
		foreach ($list as $row) {
			$content .= '<tr>
			<td align="center">'.$row['Ord_id'].'</td>
			<td align="center">'.$this->OrdersTable->getState($row['Ord_state']).'</td>
			<td align="center">'.$row['Summ'].'</td>
			<td align="center">'.((!empty($row['Delivery_date_from']) && strtotime($row['Delivery_date_from']) > 0)? date('d.m.Y H:i', strtotime($row['Delivery_date_from'])) : '').'</td>
			<td align="center">'.((!empty($row['Delivery_date_to']) && strtotime($row['Delivery_date_to']) > 0)? date('d.m.Y H:i', strtotime($row['Delivery_date_to'])) : '').'</td>
			<td align="center">'.strip_tags($row['Asm_shop']).'</td>
			<td align="center">'.strip_tags($row['Address_from']).'</td>
			<td align="center">'.$row['Address_to'].'</td>
			<td align="center">'.$row['Contact_name'].'</td>
			<td align="center">'.$row['Contact_phone'].'</td>
			<td align="center">'.((!empty($row['Ord_date']) && strtotime($row['Ord_date']) > 0)? date('d.m.Y H:i', strtotime($row['Ord_date'])) : '').'</td>
			<td align="center">'.((!empty($row['Ready_date']) && strtotime($row['Ready_date']) > 0)? date('d.m.Y H:i', strtotime($row['Ready_date'])) : '').'</td>
			<td align="center">'.$row['Task_state'].'</td>
			<td align="center">'.((!empty($row['Start_date']) && strtotime($row['Start_date']) > 0)? date('d.m.Y H:i', strtotime($row['Start_date'])) : '').'</td>
			<td align="center">'.$row['Task_ID'].'</td>
			<td align="center">'.$row['Task_type'].'</td>
			<td align="center">'.$names[$row['intExecutorID']].'</td>
			</tr>';
		}
		$content .= '</tbody></table>';

		$fileName ='deliveries_'.date('YmdHi').'.xls';

		$this->Export2excel($fileName, $content);
	}

	function OnExportAutPostDeliveries() {
		$model = new CommonModel($this->connectionEmpik);
		$list = $model->GetAutoluxUkrPostDeliveryReport();
		$names = $this->getAllUsersFio();
		usort($list, array('IndexPage','ReadyDateCMP'));
		$content = '<table><thead>
						<th>Номер заказа</th>
						<th>Статус</th>
						<th>На сумму</th>
						<th>Магазин сбора</th>
						<th>Адрес магазина</th>
						<th>Адрес доставки</th>
						<th>ФИО</th>
						<th>Телефон</th>
						<th>Заказа создан</th>
						<th>Планируемое окончание упаковки</th>
						<th>Статус задачи доставки</th>
						<th>Номер задачи доставки</th>
						<th>Тип задачи доставки</th>
						<th>Пользователь выполняет</th>
					</thead><tbody>';
		foreach ($list as $row) {
			$content .= '<tr>
			<td align="center">'.$row['Ord_id'].'</td>
			<td align="center">'.$this->OrdersTable->getState($row['Ord_state']).'</td>
			<td align="center">'.$row['Summ'].'</td>
			<td align="center">'.strip_tags($row['Asm_shop']).'</td>
			<td align="center">'.strip_tags($row['Address_from']).'</td>
			<td align="center">'.$row['Address_to'].'</td>
			<td align="center">'.$row['Contact_name'].'</td>
			<td align="center">'.$row['Contact_phone'].'</td>
			<td align="center">'.((!empty($row['Ord_date']) && strtotime($row['Ord_date']) > 0)? date('d.m.Y H:i', strtotime($row['Ord_date'])) : '').'</td>
			<td align="center">'.((!empty($row['Ready_date']) && strtotime($row['Ready_date']) > 0)? date('d.m.Y H:i', strtotime($row['Ready_date'])) : '').'</td>
			<td align="center">'.$row['Task_state'].'</td>
			<td align="center">'.$row['Task_ID'].'</td>
			<td align="center">'.$row['Task_type'].'</td>
			<td align="center">'.$names[$row['intExecutorID']].'</td>
			</tr>';
		}
		$content .= '</tbody></table>';

		$fileName ='AUPdeliveries_'.date('YmdHi').'.xls';

		$this->Export2excel($fileName, $content);
	}

	function OnExportTransfers() {
		$model = new CommonModel($this->connectionEmpik);
		$list = $model->GetTransfersReport();
		$names = $this->getAllUsersFio();
		$deps = $this->getUserAllDepartments();
		foreach ($deps as $dep) {
			$department[$dep['intVarID']] = $dep['varValue'];
		}
		usort($list, array('IndexPage','ReadyDateCMP'));
		$content = '<table><thead>
						<th>Номер заказа</th>
						<th>Статус</th>
						<th>На сумму</th>
						<th>Переместить с</th>
						<th>Переместить в</th>
						<th>Дата время начала</th>
						<th>Дата время окончания</th>
						<th>До окончания</th>
						<th>Статус задачи</th>
						<th>Номер задачи</th>
						<th>Тип задачи</th>
						<th>Пользователь выполняет</th>
					</thead><tbody>';
		foreach ($list as $row) {
			$interval = ($row['intExecutionTime'] - time() + strtotime($row['Creation_date']));
			if ($interval < 0) {
				$TimeLeft = 'Проср. на<br/>';
				$interval *= -1;
			} else {
				$TimeLeft = '';
			}
			$sec = $interval % 60;
			$interval = (int)($interval/60);
			$min = $interval % 60;
			$interval = (int)($interval/60);
			$hour = $interval % 24;
			$day = (int)($interval/24);
			if ($day > 0) {
				$TimeLeft .= $day.'д. ';
			}
			if ($hour > 0) {
				$TimeLeft .= $hour.'ч. ';
			}
			if ($min > 0) {
				$TimeLeft .= $min.'м.';
			}
			$content .= '<tr>
			<td align="center">'.$row['Ord_id'].'</td>
			<td align="center">'.$this->OrdersTable->getState($row['Ord_state']).'</td>
			<td align="center">'.$row['Summ'].'</td>
			<td align="center">'.$department[$row['From_dep']].'</td>
			<td align="center">'.strip_tags($row['Asm_shop']).'</td>
			<td align="center">'.date('d.m.Y H:i', strtotime($row['Creation_date'])).'</td>
			<td align="center">'.((!empty($row['End_date']) && strtotime($row['End_date']) > 0)? date('d.m.Y H:i', strtotime($row['End_date'])) : '').'</td>
			<td align="center">'.$TimeLeft.'</td>
			<td align="center">'.$row['Task_state'].'</td>
			<td align="center">'.$row['Task_ID'].'</td>
			<td align="center">'.$row['Task_type'].'</td>
			<td align="center">'.$names[$row['intExecutorID']].'</td>
			</tr>';
		}
		$content .= '</tbody></table>';

		$fileName ='Transfers_'.date('YmdHi').'.xls';

		$this->Export2excel($fileName, $content);
	}
	
	public function OnExportNewPostDeliveries() {
		$model = new CommonModel($this->connectionEmpik);
		$list = $model->GetNewPostDeliveryReport();
		$names = $this->getAllUsersFio();
		usort($list, array('IndexPage','ReadyDateCMP'));
		$content = '<table><thead>
						<th>Номер заказа</th>
						<th>Статус</th>
						<th>На сумму</th>
						<th>Магазин сбора</th>
						<th>Адрес магазина</th>
						<th>Адрес доставки</th>
						<th>ФИО</th>
						<th>Телефон</th>
						<th>Заказ создан</th>
						<th>Планируемое окончание упаковки</th>
						<th>Статус задачи доставки</th>
						<th>Номер задачи доставки</th>
						<th>Тип задачи доставки</th>
						<th>Пользователь выполняет</th>
					</thead><tbody>';
		foreach ($list as $row) {
			$content .= '<tr>
			<td align="center">'.$row['Ord_id'].'</td>
			<td align="center">'.$this->OrdersTable->getState($row['Ord_state']).'</td>
			<td align="center">'.$row['Summ'].'</td>
			<td align="center">'.strip_tags($row['Asm_shop']).'</td>
			<td align="center">'.strip_tags($row['Address_from']).'</td>
			<td align="center">'.$row['Address_to'].'</td>
			<td align="center">'.$row['Contact_name'].'</td>
			<td align="center">'.$row['Contact_phone'].'</td>
			<td align="center">'.((!empty($row['Ord_date']) && strtotime($row['Ord_date']) > 0)? date('d.m.Y H:i', strtotime($row['Ord_date'])) : '').'</td>
			<td align="center">'.((!empty($row['Ready_date']) && strtotime($row['Ready_date']) > 0)? date('d.m.Y H:i', strtotime($row['Ready_date'])) : '').'</td>
			<td align="center">'.$row['Task_state'].'</td>
			<td align="center">'.$row['Task_ID'].'</td>
			<td align="center">'.$row['Task_type'].'</td>
			<td align="center">'.$names[$row['intExecutorID']].'</td>
			</tr>';
		}
		$content .= '</tbody></table>';

		$fileName ='NewPostDeliveries_'.date('YmdHi').'.xls';

		$this->Export2excel($fileName, $content);
	}

	public static function DateCMP($row1, $row2) {
		$cmp1 = 0;
		if (isset($row1['Delivery_date_from'])) {
			$cmp1 = strtotime($row1['Delivery_date_from']);
		}
		$cmp2 = 0;
		if (isset($row2['Delivery_date_from'])) {
			$cmp2 = strtotime($row2['Delivery_date_from']);
		}
		if ($cmp1 == $cmp2) return 0;
		if ($cmp1 == 0) return 1;
		if ($cmp2 == 0) return -1;
		return ($cmp1 < $cmp2)? -1 : 1;
	}

	public static function ReadyDateCMP($row1, $row2) {
		$cmp1 = 0;
		if (isset($row1['Ready_date'])) {
			$cmp1 = strtotime($row1['Ready_date']);
		}
		$cmp2 = 0;
		if (isset($row2['Ready_date'])) {
			$cmp2 = strtotime($row2['Ready_date']);
		}
		if ($cmp1 == $cmp2) return 0;
		if ($cmp1 == 0) return 1;
		if ($cmp2 == 0) return -1;
		return ($cmp1 < $cmp2)? -1 : 1;
	}

	private function Export2excel($fileName, $content) {
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Pragma: private");
		header("Content-Disposition: attachment; filename=\"{$fileName}\"" );

		$header = <<<EOH
			<html xmlns:o="urn:schemas-microsoft-com:office:office"
			xmlns:x="urn:schemas-microsoft-com:office:excel"
			xmlns="http://www.w3.org/TR/REC-html40">

			<head>
			<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
			<meta name=ProgId content=Excel.Sheet>
			<!--[if gte mso 9]><xml>
			 <o:DocumentProperties>
			  <o:LastAuthor>Exite</o:LastAuthor>
			  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
			  <o:Version>1</o:Version>
			 </o:DocumentProperties>
			 <o:OfficeDocumentSettings>
			  <o:DownloadComponents/>
			 </o:OfficeDocumentSettings>
			</xml><![endif]-->
			<style>
			<!--table
				{mso-displayed-decimal-separator:"\,";
				mso-displayed-thousand-separator:"\.";}
			@page
				{margin:1.0in .75in 1.0in .75in;
				mso-header-margin:.5in;
				mso-footer-margin:.5in;}
			tr
				{mso-height-source:auto;}
			col
				{mso-width-source:auto;}
			br
				{mso-data-placement:same-cell;}
			-->
			</style>
			<!--[if gte mso 9]><xml>
			 <x:ExcelWorkbook>
			  <x:ExcelWorksheets>
			   <x:ExcelWorksheet>
				<x:Name>Exite</x:Name>
				<x:WorksheetOptions>
				 <x:Selected/>
				 <x:ProtectContents>False</x:ProtectContents>
				 <x:ProtectObjects>False</x:ProtectObjects>
				 <x:ProtectScenarios>False</x:ProtectScenarios>
				</x:WorksheetOptions>
			   </x:ExcelWorksheet>
			  </x:ExcelWorksheets>
			  <x:WindowHeight>10005</x:WindowHeight>
			  <x:WindowWidth>10005</x:WindowWidth>
			  <x:WindowTopX>120</x:WindowTopX>
			  <x:WindowTopY>135</x:WindowTopY>
			  <x:ProtectStructure>False</x:ProtectStructure>
			  <x:ProtectWindows>False</x:ProtectWindows>
			 </x:ExcelWorkbook>
			</xml><![endif]-->
			</head>

			<body link=blue vlink=purple>
EOH;
		$header.=$content;
		$header.="</body></html>";
		echo iconv("UTF-8", "windows-1251", $header);
		$this->terminatePage();
	}

	function prepareTasks(& $tasks_list, & $departments_list, & $users_list) {
		$dlist = array();
//		file_put_contents('/tmp/etasks_filter.txt', "1: "."\n",FILE_APPEND);
		if (is_array($departments_list)) {
			foreach ($departments_list as $k=>$v) {
				$dlist[$v['intVarID']] = $v['varValue'];
			}
		}
//		file_put_contents('/tmp/etasks_filter.txt', "2: "."\n",FILE_APPEND);
		$ulist = array();
		if (is_array($users_list)) {
			foreach ($users_list as $k=>$v) {
				$ulist[$v['intUserID']] = $v['varFIO'];
			}
		}
		if (is_array($tasks_list)) {
			foreach ($tasks_list as $k=>$v) {
				$tasks_list[$k]['varDepartment'] = $dlist[$tasks_list[$k]['intDepartmentID']];
				$tasks_list[$k]['varExecutor'] = $ulist[$tasks_list[$k]['intExecutorID']];
				$tasks_list[$k]['varCreator'] = $ulist[$tasks_list[$k]['intCreatorID']];
				//Hide empty dates
				if ($v['intState'] == 5) $tasks_list[$k]['varCreation'] = '0000-00-00 00:00:00';
				if ($v['varCreation'] == '0000-00-00 00:00:00'){
					$tasks_list[$k]['varCreation'] = '';
				} else {
					$tasks_list[$k]['varCreation'] = date('d.m.Y H:i', strtotime($v['varCreation']));
				}
				if ($v['varStart'] == '0000-00-00 00:00:00') {
					$tasks_list[$k]['varStart'] = '';
				} else {
					$tasks_list[$k]['varStart'] = date('d.m.Y H:i', strtotime($v['varStart']));
				}
				if ($v['varEnd'] == '0000-00-00 00:00:00') {
					$tasks_list[$k]['varEnd'] = '';
				} else {
					$tasks_list[$k]['varEnd'] = date('d.m.Y H:i', strtotime($v['varEnd']));
				}
				//Estimate what time left to do this rask
				if (in_array($v['intState'], array(1, 2))) {
					$interval = ($v['intExecutionTime'] - time() + strtotime($v['varCreation']));
					if ($interval < 0) {
						$tasks_list[$k]['varTimeLeft'] = 'Проср. на<br/>';
						$interval *= -1;
						$tasks_list[$k]['rowclass'] = 'rowred';
					} else {
						$tasks_list[$k]['varTimeLeft'] = '';
					}
					$sec = $interval % 60;
					$interval = (int)($interval/60);
					$min = $interval % 60;
					$interval = (int)($interval/60);
					$hour = $interval % 24;
					$day = (int)($interval/24);
					if ($day > 0) {
						$tasks_list[$k]['varTimeLeft'] .= $day.'д. ';
					}
					if ($hour > 0) {
						$tasks_list[$k]['varTimeLeft'] .= $hour.'ч. ';
					}
					if ($min > 0) {
						$tasks_list[$k]['varTimeLeft'] .= $min.'м.';
					}
				}
				//Get Comments
				$order = $this->OrdersTable->Get(array('Ord_id' => $v['intOrderID']));
				$tasks_list[$k]['ManagerComment'] = $order['Adm_comment'];
				$tasks_list[$k]['ClientComment'] = $order['Ord_comment'];
				$tasks_list[$k]['is_preorder'] = $order['is_preorder'];

                //Get Username
                $acc = $this->accountsTable->get(array('id'=>$order['User_id']));
                $tasks_list[$k]['varUserName'] = $acc['login'];
			}
		}

//		file_put_contents('/tmp/etasks_filter.txt', "$dlist: ".$dlist."\n",FILE_APPEND);
		return $dlist;
	}

}

Kernel::ProcessPage(new IndexPage("index.tpl"));