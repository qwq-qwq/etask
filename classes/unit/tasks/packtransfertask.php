<?php

Kernel::Import('classes.unit.tasks.PackTask');

class PackTransferTask extends PackTask {

	function OnSetExecutor() {
		//echo "test";die();
		//Get Articles
		$articles = $this->packArticlesTable->GetByFields(array('intTaskID' => $this->task_data['intID']), null, false);
		//Check 0s
		foreach ($articles as $k => $art) {
			if ($art['intDemandQty'] == 0) {
				unset($articles[$k]);
			}
		}
		//Get From shopID
		$from_shop_id = 0;
		foreach ($this->page->getUserAllDepartments() as $dep) {
			if ($dep['intVarID'] == $this->task_data['intDepartmentID']) {
				$from_shop_id = $dep['intCodeShopSprut'];
				break;
			}
		}
		//Get Order data
		$ord = $this->ordersTable->Get(array('Ord_id' => $this->task_data['intOrderID']));
		//Change invoice state second time?
		if ($this->task_data['intType'] == 60) {//Transport company - NO
			$change_state_2 = false;
		} else {
			$change_state_2 = true;
		}
		//Create Invoice
		$inv = $this->sprutModel->CreateInvoice($ord, $articles, $from_shop_id, $ord['Asm_shop_id'], true, $change_state_2);
		if (is_array($inv)) {//All OK proceed
			list($code_woi, $num_woi, $total_qty) = $inv;
			//Insert this invoice record
			$data = array(
				'intTaskID' => $this->task_data['intID'],
				'intOrderID' => $this->task_data['intOrderID'],
				'intCodeInvoice' => $code_woi,
				'intNumberInvoice' => $num_woi,
				'intQty' => $total_qty,
				'intShopFrom' => $from_shop_id,
				'intShopTo' => $ord['Asm_shop_id'],
				'varStatus' => 'C'
			);
			$inv_id = $this->InvoicesTable->Insert($data);
			//Insert invoice articles records
			foreach ($articles as $art) {
				$data = array(
					'intArticleID' => $art['intArticleID'],
					'intInvoiceID' => $inv_id,
					'intTaskID' => $this->task_data['intID'],
					'varArticleName' => $art['varArticleName'],
					'intDemandQty' => $art['intDemandQty']
				);
				$this->InvoiceArticlesTable->Insert($data);
			}
			parent::OnSetExecutor();
		} else {
			$msg = 'TASK[PackTransferTask]: '.var_export($this->task_data, true);
			$msg .= "\nARTICLES:".var_export($articles, true);
			$msg .= "\nORDER:".var_export($ord, true);
			$msg .= "\nFrom_shop:".var_export($from_shop_id, true);
			$msg .= "\nRes:".var_export($inv, true);
			mail('developers@bukva.ua', 'pack_task', $msg);
		}
	}

	function onDoneTask() {
		//Unlock child task
		$data = array(
			'intID' => $this->task_data['intChildID'],
			'intState' => 1
		);
		$this->page->tasksTable->update($data);
		//Exec parent code
		parent::onDoneTask();
	}

}