<?php

Kernel::Import('classes.unit.tasks.Task');
Kernel::Import('classes.data.etasks.InvoiceArticlesTable');
Kernel::Import('classes.data.etasks.InvoicesTable');
Kernel::Import('classes.data.etasks.TasksTable');

class TransferTask extends Task {

	/**
	 * @var invoicesTable
	 * @see classes/data/invoicesTable.php
	 */
	protected $invoicesTable;
	/**
	 * @var invoiceArticlesTable
	 * @see classes/data/invoiceArticlesTable.php
	 */
	protected $invoiceArticlesTable;
	/**
	 * @var tasksTable
	 * @see classes/data/tasksTable.php
	 */
	protected $tasksTable;

	function __construct(&$page, $data) {
		parent::__construct($page, $data);
		$this->template = 'TransferTask.tpl';
		$this->invoicesTable = new InvoicesTable($this->page->getConnection());
		$this->invoiceArticlesTable = new InvoiceArticlesTable($this->page->getConnection());
		$this->tasksTable = new TasksTable($this->page->getConnection());
	}

	function onDoneTask() {
		$intTaskID = $this->page->getRequest()->getNumber('intTaskID');

		//Find parent task
		$parent = $this->page->tasksTable->GetByFields(array('intChildID' => $this->task_data['intID']));
		//Bring invoice to end state
		$invoice = $this->invoicesTable->GetByFields(array('intTaskID' => $parent['intID']));
		if (count($invoice)) {
			$invoice['varStatus'] = 'E';
			$invoice['varExtStatus'] = 'Переведён в конечное состояние вручную.';
			$this->invoicesTable->Update($invoice);
			$this->sprutModel->InvoiceToEndState($invoice['intCodeInvoice']);

			$invoiceArticles = $this->invoiceArticlesTable->GetByFields(array('intInvoiceID' => $invoice['intID']), null, false);

			foreach ($invoiceArticles as $key => $value) {
				$value['intDoneQty'] = $value['intDemandQty'];
				$this->invoiceArticlesTable->Update($value);
			}
		}
		//Unlock child if it was the last parent done.
		$parents = $this->page->tasksTable->GetByFields(array('intChildID' => $this->task_data['intChildID']), null, false);
		$unlock = true;
		foreach ($parents as $parent) {
			if (!in_array($parent['intState'], array(3, 4, 6)) && $parent['intID'] != $intTaskID) {
				$unlock = false;
				break;
			}
		}
		if ($unlock) {
			$upd = array(
				'intID' => $this->task_data['intChildID'],
				'intState' => 1
			);
			$this->page->tasksTable->Update($upd);
			//Update order
			$upd = array(
				'Ord_id' => $this->task_data['intOrderID'],
				'Goods_state' => 10,
				'Ord_state' => 40,
				'Ord_changed_date' => date('Y-m-d H:i:s')
			);
			$this->ordersTable->Update($upd);
		}
		parent::OnPerformed();
	}

	function render(){
		parent::render();
		$this->page->GetDocument()->addValue('usefile', TEMPLATES_TASKS_PATH.$this->template);
		//Find parent task
		$parent = $this->page->tasksTable->GetByFields(array('intChildID' => $this->task_data['intID']));
		$invoice = $this->invoicesTable->GetByFields(array('intTaskID' => $parent['intID']));

		$departments = $this->page->getUserAllDepartments();
		foreach ($departments as $key => $value) {
			if ($value['intCodeShopSprut'] == $invoice['intShopFrom']) $invoice['shopFromName'] = $value['varValue'];
			if ($value['intCodeShopSprut'] == $invoice['intShopTo']) $invoice['shopToName'] = $value['varValue'];
		}
		$this->page->getDocument()->addValue('invoice', $invoice);

		// get task comments
		$this->prepareComments();
	}

}