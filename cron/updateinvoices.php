<?php
include_once(realpath(dirname(__FILE__)."/../classes/variables.php"));
Kernel::Import("system.page.Page");
//Models
Kernel::Import("classes.data.sprut.SprutModel");
Kernel::Import("classes.data.etasks.TasksTable");
Kernel::Import('classes.data.etasks.InvoiceArticlesTable');
Kernel::Import('classes.data.etasks.InvoicesTable');
Kernel::Import('classes.data.empik.OrdersTable');

Class UpdateInvoices extends Page {
	
	private $SprutModel;
	private $TasksTable;
	private $InvoiceArticlesTable;
	private $InvoicesTable;
	private $OrdersTable;
	
	function __construct() {
		parent::__construct('void.tpl');
		$this->SprutModel = new SprutModel(DB_SPRUT_CONNECT_PARAMS);
		$this->TasksTable = new TasksTable($this->connection);
		$this->InvoicesTable = new InvoicesTable($this->connection);
		$this->InvoiceArticlesTable = new InvoiceArticlesTable($this->connection);
		$this->OrdersTable = new OrdersTable($this->connectionEmpik);
	}
	
	function Index() {
		//Get'em
		$invoices = $this->InvoicesTable->GetUndoneInvoices();
		foreach ($invoices as $invoice) {
			//Update them
			$sprut_inv = $this->SprutModel->GetInvoice($invoice['intCodeInvoice']);
			if (empty($sprut_inv)) {
				$invoice['varStatus'] = 'C';
				$invoice['varExtStatus'] = 'Накладная не найдена в ПС СПРУТ';
			} else {
				$invoice['varStatus'] = trim($sprut_inv['STATE_WRITE_OFF_INVOICE']);
				$invoice['varExtStatus'] = iconv('WINDOWS-1251', 'UTF-8', $sprut_inv['EXT_STATE']);
			}
			$this->InvoicesTable->update($invoice);
//var_dump('<pre>INVOICE',$invoice);
			if ($invoice['varStatus'] == 'E') {//Task done
				//Update invoice articles
				$goods = $this->InvoiceArticlesTable->GetByFields(array('intInvoiceID' => $invoice['intID']), null, false);
//var_dump('precess', $goods);
				foreach ($goods as $article) {
					$article['intDoneQty'] = $article['intDemandQty'];
					$this->InvoiceArticlesTable->Update($article);
				}
				//Update task
				$task = $this->TasksTable->Get(array('intID' => $invoice['intTaskID']));
				//Check if invoice attached to a transfer or packing task
				if (in_array($task['intType'], array(70, 75, 80))) {
					$transfer_task_id = $task['intID'];
				} else {
					$transfer_task_id = $task['intChildID'];
				}
				$upd = array(
					'intID' => $transfer_task_id,
					'intState' => 3,
					'varEnd' => date("Y-m-d H:i:s")
				);
//var_dump('task', $upd);
				$this->TasksTable->Update($upd);
				//Unlock child if it was the last parent done.
				$task = $this->TasksTable->Get($upd);
				$parents = $this->TasksTable->GetByFields(array('intChildID' => $task['intChildID']), null, false);
				$unlock = true;
				foreach ($parents as $parent) {
					if (!in_array($parent['intState'], array(3, 4, 6))) {
						$unlock = false;
						break;
					}
				}
				if ($unlock) {
					$upd = array(
						'intID' => $task['intChildID'],
						'intState' => 1
					);
//var_dump('child', $upd);
					$this->TasksTable->Update($upd);
					//Update order
					$upd = array(
						'Ord_id' => $task['intOrderID'],
						'Goods_state' => 10,
						'Ord_state' => 40,
						'Ord_changed_date' => date('Y-m-d H:i:s')
					);
//var_dump('order', $upd);
					$this->OrdersTable->Update($upd);
				}
			}
		}
	}
}

Kernel::ProcessPage(new UpdateInvoices());