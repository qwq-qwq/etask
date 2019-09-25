<?php

Kernel::Import('classes.unit.tasks.Task');
Kernel::Import('classes.data.etasks.PackArticlesTable');
Kernel::Import('classes.data.etasks.TasksTable');
Kernel::Import('classes.data.etasks.InvoiceArticlesTable');
Kernel::Import('classes.data.etasks.InvoicesTable');

class PackTask extends Task {

	/**
	 * @var packArticlesTable
	 * @see classes/data/packArticlesTable.php
	 */
	protected $packArticlesTable;
	/**
	 * @var tasksTable
	 * @see classes/data/tasksTable.php
	 */
	protected $tasksTable;
	/**
	 * @var InvoicesTable
	 * @see classes/data/InvoicesTable.php
	 */
	protected $InvoicesTable;
	/**
	 * @var InvoiceArticlesTable
	 * @see classes/data/InvoiceArticlesTable.php
	 */
	protected $InvoiceArticlesTable;

	function __construct(&$page, $data) {
		parent::__construct($page, $data);
		$this->template = 'PackTask.tpl';
		$this->packArticlesTable = new PackArticlesTable($this->page->getConnection());
		$this->tasksTable = new TasksTable($this->page->getConnection());
		$this->InvoicesTable = new InvoicesTable($this->page->getConnection());
		$this->InvoiceArticlesTable = new InvoiceArticlesTable($this->page->getConnection());
	}

	function onDoneTask() {
		$dem_qtys = $this->page->getRequest()->Value('dem_Qty_');
		$data['intTaskID'] = $this->page->getRequest()->getNumber('intTaskID');
		$intTaskID = $this->page->getRequest()->getNumber('intTaskID');

		if(!empty($dem_qtys) && is_array($dem_qtys)) {
			foreach ($dem_qtys as $key => $value) {
				$data['intDemandQty'] = $value;
				$data['intDoneQty'] = $value;
				$data['intID'] = $key;

				$this->packArticlesTable->Update($data);
			}
			parent::OnPerformed();
		}

		$this->page->getResponse()->redirect('task.php?ID='.$intTaskID);
	}

	function render(){
		parent::render();
		$this->page->GetDocument()->addValue('usefile', TEMPLATES_TASKS_PATH.$this->template);

		$goods = $this->packArticlesTable->getList(array('intTaskID' => $this->task_data['intID']));

		$this->page->getDocument()->addValue('goods', $goods);

		// get task comments
		$this->prepareComments();
	}

}