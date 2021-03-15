<?php

Kernel::Import('classes.unit.tasks.CollectTask');

class CollectOrderTask extends CollectTask {

	protected $exec_time = 3600;

	function MakeReserve() {

        $departmentsTable = new DepartmentsTable($this->page->getConnectionIntranet());
        $dep = $departmentsTable->Get(array('intVarID' => $this->task_data["intDepartmentID"]));

        $res = $this->sprutModel->query("select MZ.GetTradeHallFromShop(".$dep['intCodeShopSprut'].") as WH_FROM from dual");
        $TradeHallFromShop = $res[0]['WH_FROM'];

        // Bukva shop
        $res = $this->sprutModel->query("select MZ.GetTradeHallFromShop(79) as WH_TO from dual");
        $TradeHallToShop = $res[0]['WH_TO'];
             //Windows-1251
        $comment = iconv('UTF-8', 'Windows-1251', 'Передаточная создана по заказу bukva.ua №'.$this->order_data['Ord_id'].' для резервирования товаров №'.$this->task_data['intID']);

        $SQL = "declare
				code_woi# integer;
				num_woi# integer;
				qty# integer;
				res# integer;
				begin

				code_woi#:=mz.GetCodeWriteOffInvoice;

				num_woi#:=mz.GetNUmberWriteOffInvoice;

				insert into MZ.WRITE_OFF_INVOICE
				 (CODE_WRITE_OFF_INVOICE, CODE_SHOP, CODE_SUBGROUP, NUMBER_WRITE_OFF_INVOICE,
				DATE_WRITE_OFF_INVOICE, CODE_ADDITION_SIGN, CODE_PATTERN,
				CODE_FIRM, CODE_WAREHOUSE, VARIETY_WRITE_OFF_INVOICE, TYPE_WRITE_OFF_INVOICE, CHANGE_STATE, TYPE_PRICE,
				TYPE_ADDRESSEE, CODE_ADDRESSEE, CODE_WAREHOUSE_ADDRESSEE, CODE_SHOP_ADDRESSEE,DESCRIPTION,CODE_DEALER)
				values (code_woi#,".$dep['intCodeShopSprut'].",1,num_woi#,trunc(sysdate),-4,1902, ".$this->sprutModel->getFirmByShop($dep['intCodeShopSprut']).",".$TradeHallFromShop.",'T','O','02','PD','F',".$this->sprutModel->getFirmByShop(79).",".$TradeHallToShop.",79,'".$comment."' ,MZ.GETCODEDEALERFROMSHOP(".$dep['intCodeShopSprut']."));
				
				insert into mz.bukva_order_invoice (code_order, code_write_off_invoice, order_status, order_comment) 
				values (".$this->order_data['Ord_id'].", code_woi#, '', '".$comment."');
				
				\n";
        $code_wares = $this->page->getRequest()->Value('code_wares');
        $qtys = $this->page->getRequest()->Value('Qty');
        $thereAreRows = false;
        if (is_array($qtys)) {
            foreach ($qtys as $key => $value) {
                if($value > 0){
                    $thereAreRows = true;
                    $SQL .= "res#:=MZ.SPR\$_WRITE_OFF_INVOICE.INSWARESTOWRITEOFFINVOICE(code_woi#,".$code_wares[$key].",19,".$value.",0,-1,0,'',0,'',0,0,0,1,'C',0);\n";
                }
            }
        }

        $SQL .= "end;\n";

        if ($thereAreRows){
            $res = $this->sprutModel->query($SQL);
            if ($res === FALSE){
                die('ERROR! '.$SQL);
            }
            //echo "<br>" . $SQL;
        }
    }

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
				// разброликорать след. задачу если все на этом уровне в (выполнен, отказ, ожидается)
				if (!$this->tasksTable->isHasUnfinishedTasks($this->task_data['intChildID'])) {
					$this->tasksTable->unlockNextTask($this->task_data['intChildID']);
				}
				// в магазине обновить статус заказа на "ожидается товар"
				$order = array('Ord_id'=>$this->task_data['intOrderID'], 'Ord_state'=>30);
				$this->ordersTable->Update($order);
			}
            //$this->MakeReserve();
			$this->page->getResponse()->redirect('index.php');
		}
	}

}