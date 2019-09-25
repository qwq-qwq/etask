<?php

Kernel::Import('classes.unit.tasks.PackTask');
Kernel::Import('classes.data.empik.CatalogTreeTable');

class PackOrderTask extends PackTask {

	function OnSetExecutor(){
		//$test = $this->sprutModel->test();
		//Get Order data
		$order = $this->ordersTable->Get(array('Ord_id' => $this->task_data['intOrderID']));
		//Get payment type
		$payment_type = $this->paymentTypesTable->Get(array('Payment_type' => $order['Payment_type']));
		//Get goods
		$goods = $this->salesTable->GetByFields(array('Ord_id'=>$this->task_data['intOrderID']), null, false);
		foreach ($goods as $k => $v) {
			if (strtoupper($v['Discount_forbidden']) == 'YES') {
				$goods[$k]['discount'] = 0;
			} else {
				$goods[$k]['discount'] = max($v['discount'], $order['discount']);
			}
			$goods[$k]['PriceDiscount'] = $v['Price'] - $v['Price']*$goods[$k]['discount']/100;
			$goods[$k]['Sum'] = $goods[$k]['PriceDiscount']*$v['Qty'];
		}
		
		//Add Delivery article
		if($order['Overcost'] > 0){
			switch ($order['Delivery_type']) {
				case 1:
					$art_id = 224955; //courier
					break;
				case 2:
					$art_id = 225183; //Autolux
					break;
				case 3:
					$art_id = 267931;//Self-delivery
					break;
				case 4:
					$art_id = 225159; //UkrPost
					break;
				case 5:
					$art_id = 225159; //NewPost
					break;
				default:
					return;
			}
			$deliv = array(
				'Wares_id' => $art_id,
				'Qty' => 1,
				'Price' => $order['Overcost'],
				'PriceDiscount' => $order['Overcost'],
				'Vat' => 20
			);
			$goods[] = $deliv;
		}
		//User related data
		$code_privat = 0;
		if ($order['User_id'] > 0) {
			$user = $this->accountsTable->Get(array('id' => $order['User_id']));
			$code_privat = $user['code_privat'];
		}
		//Cash?
		if (strtolower($payment_type['cash']) == 'yes') {
			if (!$this->sprutModel->isNotExistPreorder($this->task_data['intOrderID'])) {
				$taskID = $this->page->getRequest()->getNumber('intTaskID');
				$this->page->getResponse()->redirect('/task.php?ID='.$taskID.'#preorder_exist');
			}
			//Create preorder
			$res = $this->sprutModel->CreatePreorder($order, $goods, $code_privat);
			//Update intermediary order data
			if (is_array($res)) $order['Barcode_pos'] = $res[2];
			//Prepare $goods to create invoice to Empik.ua
			if($order['Overcost'] > 0) {//Remove delivery article
				@array_pop($goods);
			}
			foreach ($goods as &$article) {
				$article['intArticleID'] = $article['Wares_id'];
				$article['intDemandQty'] = $article['Qty'];
			}unset($article);
			//Create invoice to Empik.ua
			$inv = $this->sprutModel->CreateInvoice($order, $goods, $order['Asm_shop_id'], 79, false);
			//Insert it into e-task
			if (is_array($inv)) {//All OK proceed
				list($code_woi, $num_woi, $total_qty) = $inv;
				//Insert this invoice record
				$inv_task_id = $this->task_data['intID'];

				$data = array(
					'intTaskID' => $inv_task_id,
					'intOrderID' => $this->task_data['intOrderID'],
					'intCodeInvoice' => $code_woi,
					'intNumberInvoice' => $num_woi,
					'intQty' => $total_qty,
					'intShopFrom' => $order['Asm_shop_id'],
					'intShopTo' => 79,
					'varStatus' => 'C'
				);
				$inv_id = $this->InvoicesTable->Insert($data);
				//Insert invoice articles records
				foreach ($goods as $art) {
					$data = array(
						'intArticleID' => $art['intArticleID'],
						'intInvoiceID' => $inv_id,
						'intTaskID' => $inv_task_id,
						'varArticleName' => $art['Name'],
						'intDemandQty' => $art['intDemandQty']
					);
					$this->InvoiceArticlesTable->Insert($data);
				}
			}
		} else {//Create order
			$res = $this->sprutModel->CreateOrder($order, $goods);
		}
		//Check result
		if (is_array($res)) {
			//Update order data
			list($sp_ord_id, $sp_ord_num, $barcode) = $res;
			$upd = array(
				'Ord_id' => $order['Ord_id'],
				'Sprut' => $sp_ord_id,
				'Barcode_pos' => $barcode,
				'Sprut_num' => $sp_ord_num,
			);
			$this->ordersTable->Update($upd);

			parent::OnSetExecutor();
		} else {
			$msg = 'TASK[PackOrderTask]: '.var_export($this->task_data, true);
			$msg .= "\nARTICLES:".var_export($goods, true);
			$msg .= "\nORDER:".var_export($order, true);
			$msg .= "\nRes:".var_export($res, true);
			//mail('developers@miritec.com', 'pack_order', $msg);
			mail('qwq-qwq@yandex.ru', 'pack_order', $msg);
			$this->errors = var_export($res, true);
		}
	}

	function onDoneTask() {
		//Unlock task's child
    $payment_type = $this->paymentTypesTable->Get(array('Payment_type' => $this->order_data['Payment_type']));
		if (strtolower($payment_type['cash']) == 'no' && $this->order_data['Pay_state'] == 0 && !in_array($payment_type['Payment_type'],array(6,9))) {
      return;
    }
		if ($this->task_data['intChildID'] > 0) {
			$this->page->tasksTable->unlockNextTask($this->task_data['intChildID']);
		}
		if ($this->order_data['Delivery_type'] == 3) {
			$ord = array(
				'Ord_id' => $this->task_data['intOrderID'],
				'Ord_state' => 50
			);
			$this->ordersTable->Update($ord);
		}
		if($this->order_data['Edrpou']) {
			$smarty = new Smarty();
			$smarty->template_dir = TEMPLATES_PATH.'admin/mail/';
			$smarty->compile_dir = PROJECT_CACHE.'smarty/';
			$smarty->config_dir = TEMPLATES_PATH.'admin/mail/';
			$smarty->cache_dir = PROJECT_CACHE.'smarty/';
			$smarty->caching = false;
			$smarty->debugging = ENABLE_INTERNAL_DEBUG;
			
			$smarty->assign('data', $this->order_data);
			$contentdata = $smarty->fetch('reporttax.tpl');

			$msg = new MailMessage();
			$msg->setFrom('Bukva shop <noreply@bukva.ua>');
			$msg->setSubject('Клиенту требуется Налоговая накладная '.date('d.m.Y H:i:s'));
			$filename = '/tmp/report_'.date('d.m.Y H:i:s').'.xls';
			file_put_contents($filename, $contentdata);
			$msg->setAttachment($filename);
			//new SendMailMessage('vitaliy.korzh@miritec.com', $msg);
			new SendMailMessage('delivery@bukva.ua', $msg);
		}
		parent::onDoneTask();
	}

	protected function getOrderGoods() {
		$sales = $this->salesTable->GetByFields(array('Ord_id'=>$this->task_data['intOrderID']), null, false);
		foreach ($sales as $k => $v) {
			if (strtoupper($v['Discount_forbidden']) == 'YES') {
				$sales[$k]['discount'] = 0;
			} else {
				$sales[$k]['discount'] = max($v['discount'], $this->order_data['discount']);
			}
			$sales[$k]['PriceDiscount'] = $v['Price'] - $v['Price']*$sales[$k]['discount']/100;
			$sales[$k]['Sum'] = $sales[$k]['PriceDiscount']*$v['Qty'];
		}
		return $sales;
	}

	function render() {
		parent::render();
		//Check if order payed if it's payment type is cashless
		$payment_type = $this->paymentTypesTable->Get(array('Payment_type' => $this->order_data['Payment_type']));
		$not_payed_cashless = false;
		if (strtolower($payment_type['cash']) == 'no' && $this->order_data['Pay_state'] == 0 && !in_array($payment_type['Payment_type'],array(6,9))) {
			$not_payed_cashless = true;
		}
		$this->page->GetDocument()->addValue('not_payed_cashless', $not_payed_cashless);
		//Make cards notification
		$card_note = '';
		$user = array();
		if ($this->order_data['User_id'] > 0) {
			$user = $this->accountsTable->Get(array('id' => $this->order_data['User_id']));

			 if ($user['discount_code'] == 8) {
			 	$card_note .= '<span style="background-color:#3A723D;text-align:center;-moz-border-radius:2px 2px 2px 2px; color:white; padding:2px 4px; white-space:nowrap; font-size: 11px">КЛУБНАЯ КАРТА '.$user['bar_code'].'</span>';
			 } elseif ($user['discount_code'] == 2) {
			 	$card_note .= '<span style="background-color:black;text-align:center;-moz-border-radius:2px 2px 2px 2px; color:white; padding:2px 4px; white-space:nowrap; font-size: 11px">VIP КАРТА '.$user['bar_code'].'</span>';
			 } else {
			 	$has_book = false;
			 	$sales =  $this->getOrderGoods();
			 	$this->CatalogTreeTable = new CatalogTreeTable($this->page->getConnectionEmpik());
				foreach ($sales as $article) {
					if ($this->CatalogTreeTable->isBook($article['Group_id'])) {
						$has_book = true;
						break;
					}
				}
				/*
				if ($has_book) {
					$card_note .= 'В данный заказ необходимо вложить клубную карту.';
				}
				*/
			 }
			 //Has no vip
			 if ($user['discount_code'] != 2) {
			 	$sumPlusCurrent = $this->sprutModel->GetRetailSum($user['code_privat']);
				$sumPlusCurrent += $user['accumulate_sum'];
				if ($this->order_data['Pay_state'] != 1) $sumPlusCurrent += $this->order_data["Cost"];
				if ($sumPlusCurrent > 1000 && $user['rejected_vip'] == 0) {
					$card_note = ' Предложите покупателю VIP карту.';
				}
			 }
		}

		$this->page->GetDocument()->addValue('order', $this->order_data);
		$this->page->GetDocument()->addValue('errors', $this->errors);
		$this->page->GetDocument()->addValue('card_note', $card_note);
		$this->page->GetDocument()->addValue('total_cost', $this->order_data["Cost"]);
	}

}