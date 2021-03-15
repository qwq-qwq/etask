<?php

Kernel::Import('classes.unit.tasks.CollectTask');

class CollectMovementTask extends CollectTask {

	protected $exec_time = 3600;

    function onDoneTask() {
		$nedostach = $this->page->getRequest()->getNumber('nedostach');
		$code_wares = $this->page->getRequest()->Value('code_wares');
		$qtys = $this->page->getRequest()->Value('Qty');
		$dem_qtys = $this->page->getRequest()->Value('dem_Qty_');
		$data['intTaskID'] = $this->page->getRequest()->getNumber('intTaskID');
		$intTaskID = $this->page->getRequest()->getNumber('intTaskID');
		if(!empty($qtys) && is_array($qtys)) {
			$otkazFlag = true;
			$chastichnoFlag = false;

			foreach ($qtys as $key => $value) {
				$data['intDemandQty'] = $dem_qtys[$key];
				$data['intDoneQty'] = $value;
				$data['intID'] = $key;

				$this->collectArticlesTable->Update($data);

				if($value != 0) {
					$otkazFlag = false;
				}
				if($value != $dem_qtys[$key]) {
					$chastichnoFlag = true;
					$prods[$code_wares[$key]] = intval($dem_qtys[$key] - $value);
				}
			}

			if($otkazFlag) {
				if ($nedostach) $this->RegisterNedostach($prods);
				// update with отказ 4
				$d = array('intID' => $intTaskID, 'intState' => 4, 'varEnd' => date("Y-m-d H:i:s"));
				$this->tasksTable->Update($d);
				// удаляем все новые и заблокированные
				$this->tasksTable->clearNewLockedByOrderID($this->task_data['intOrderID']);
				// генерируем задачу для колцентра
				$this->tasksTable->generateCallcentreEditTask($this->task_data['intOrderID'], $this->task_data['intExecutorID']);

			} elseif($chastichnoFlag) {
				if ($nedostach) $this->RegisterNedostach($prods);
				// update with частично 6
				$d = array('intID' => $intTaskID, 'intState' => 6, 'varEnd' => date("Y-m-d H:i:s"));
				$this->tasksTable->Update($d);
				// удаляем все новые и заблокированные
				$this->tasksTable->clearNewLockedByOrderID($this->task_data['intOrderID']);
				// генерируем задачу для колцентра
				$this->tasksTable->generateCallcentreEditTask($this->task_data['intOrderID'], $this->task_data['intExecutorID']);

			} else {
				// update with выполнена 3
				$d = array('intID' => $intTaskID, 'intState' => 3, 'varEnd' => date("Y-m-d H:i:s"));
				$this->tasksTable->Update($d);
				// разброликорать след. задачу
				$this->tasksTable->unlockNextTask($this->task_data['intChildID']);
			}
			$this->page->getResponse()->redirect('index.php');
		}
	}

}
