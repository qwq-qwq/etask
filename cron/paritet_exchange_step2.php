<?php
include_once(realpath(dirname(__FILE__)."/../classes/variables.php"));

Kernel::Import("system.page.Page");
Kernel::Import("classes.data.sprut.SprutModel");
Kernel::Import("classes.data.etasks.TasksTable");

Class ParitetExchangeStep2 extends Page {

	function _log($t, $mixed = null) {
		echo "[".$t."] ";
		if (is_array($mixed)) {
			print_r($mixed);
		} else {
			echo $mixed;
		}
		echo "\n";
	}

	function __construct() {
		parent::__construct('void.tpl');
		$SprutModel = new SprutModel(DB_SPRUT_CONNECT_PARAMS);
		$TasksTable = new TasksTable($this->connection);

		$SQL = "SELECT * FROM tasks WHERE varComment!='' and intState IN (1,2) and intType IN (70, 75, 80) limit 100"; // limited to prevent slow speed
		$transferTasks = $this->connection->ExecuteScalar($SQL, false);

		if (is_array($transferTasks)) {
			foreach ($transferTasks as $task) {
				$this->_log('Check for task', $task['intID']);
				$SQL = "select count(*) as taskdone from mz.invoice i where i.state_invoice='W' and i.number_invoice=".intval($task['varComment']);

				$this->_log('Check for number_invoice', intval($task['varComment']));
				$res = $SprutModel->query($SQL);
				if ($res === FALSE) {
					$this->_log('paritet_exchange_step2 line '.__LINE__.' sql error', $SQL);
					mail('developer@bukva.ua', 'paritet_exchange line '.__LINE__.' sql error', $SQL);
					continue;
				}
				if ($res[0]['TASKDONE'] == 0) {
					$this->_log('Not done yet');
					continue; // next if zero
				}
				$this->_log('Close task', $task['intID']);
				// закрываем задачу перемещения
				$task["intState"] = 3; // выполнена
				$task["varStart"] = date('Y-m-d H:i:s');
				$task["varEnd"] = date('Y-m-d H:i:s');
				$TasksTable->Update($task);
				$this->_log('done task', $task);
				// разброликорать след. задачу
				$TasksTable->unlockNextTask($task['intChildID']);
			}
		} else {
			$this->_log('Nothing to do here');
		}
		$this->_log('DONE!');
	}

}

Kernel::ProcessPage(new ParitetExchangeStep2());
