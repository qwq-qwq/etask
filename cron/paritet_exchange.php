<?php
include_once(realpath(dirname(__FILE__)."/../classes/variables.php"));

Kernel::Import("system.page.Page");
Kernel::Import("classes.data.sprut.SprutModel");
Kernel::Import("classes.data.etasks.TasksTable");
Kernel::Import('classes.data.empik.OrdersTable');
Kernel::Import('classes.data.etasks.CollectArticlesTable');
Kernel::Import('classes.data.etasks.PackArticlesTable');
Kernel::Import('classes.data.intranet.DepartmentsTable');
Kernel::Import('classes.data.etasks.InvoiceArticlesTable');
Kernel::Import('classes.data.etasks.InvoicesTable');
Kernel::Import('classes.unit.paritet');

Class ParitetExchangeService extends Page {

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
		$OrdersTable = new OrdersTable($this->connectionEmpik);
		$collectArticlesTable = new CollectArticlesTable($this->connection);
		$packArticlesTable = new PackArticlesTable($this->connection);
		$InvoicesTable = new InvoicesTable($this->connection);
		$InvoiceArticlesTable = new InvoiceArticlesTable($this->connection);
		$departmentsTable = new DepartmentsTable($this->connectionIntranet);

		$ParitetExchange = new ParitetExchange($SprutModel);

		$res = $ParitetExchange->getFilesList('./stock/');
		$downloaded = array();
		foreach ($res as $flag) {
			$this->_log('found file', $flag);
			// download files
			$T = $ParitetExchange->getFile(str_replace('.flg', '.svb', $flag));
			$expanded = explode("\n", $T);
			$header = explode(",", $expanded[0]);
			unset($expanded[0]);
			$zadacha_sbora_id = intval(trim($header[1],'"'));
			$task = $TasksTable->Get(array('intID' => $zadacha_sbora_id));
			if (count($task)) {
				$this->_log('found task', $task["intID"]);
				// update task
				$task["varStart"] = date('Y-m-d H:i:s');
				$task["intState"] = 2; // взята в работу
				$TasksTable->Update($task);
				// process products
				$dem_qty = $collectArticlesTable->GetList(array('intTaskID' => $task["intID"]));
				$this->_log('found collectArticles', $dem_qty);
				$paritet_prods = array();
				$paritet_prods_price = array();
				if(is_array($expanded)) {
					foreach ($expanded as $line) {
						$raw_prod = explode(",", $line);
						$this->_log('process file line', $line);
						$ttt = trim($raw_prod[2],'"');
						$this->_log('get code_wares for article', (empty($ttt)?'EMPTY!':$ttt));
						if (!empty($ttt)) {
							$real_row = $SprutModel->GetWaresIdBySupplierArticle($ttt);
							$this->_log('found code_wares for article', $real_row);
							if (!empty($real_row)) {
								$paritet_prods_price[$real_row] = trim($raw_prod[4],'"');
								$paritet_prods[$real_row] = trim($raw_prod[3],'"');
							}
						}
					}
				}
				$this->_log('found goods', $paritet_prods);
				$this->_log('found goods prices', $paritet_prods_price);
				$chastichnoFlag = false;
				$otkaz = true;
				foreach ($dem_qty as $dqty) {
					if ($dqty['intDemandQty'] != $paritet_prods[$dqty["intArticleID"]]) {
						$chastichnoFlag = true;
					}
					if ($paritet_prods[$dqty["intArticleID"]] > 0) {
						$otkaz = false;
					}
					$dqty['intDoneQty'] = intval($paritet_prods[$dqty["intArticleID"]]);
					$dqty['varPrice'] = ($paritet_prods_price[$dqty['intArticleID']]/1.2)/1.2;
					$this->_log('update collectarticles', array($dqty["intArticleID"] => $dqty['intDoneQty']));
					$collectArticlesTable->Update($dqty);
				}
				if($otkaz) {
					$task["intState"] = 4; // отказ
					$task["varEnd"] = date('Y-m-d H:i:s');
					$TasksTable->Update($task);
					// удаляем все новые и заблокированные
					$TasksTable->clearNewLockedByOrderID($task['intOrderID']);
					// генерируем задачу для колцентра
					$TasksTable->generateCallcentreEditTask($task['intOrderID'], $task['intExecutorID']);
					$this->_log('otkaz callcentre help me!');
				} elseif($chastichnoFlag) {
					$task["intState"] = 6; // частично выполнена
					$task["varEnd"] = date('Y-m-d H:i:s');
					$TasksTable->Update($task);
					// удаляем все новые и заблокированные
					$TasksTable->clearNewLockedByOrderID($task['intOrderID']);
					// генерируем задачу для колцентра
					$TasksTable->generateCallcentreEditTask($task['intOrderID'], $task['intExecutorID']);
					$this->_log('chastichno callcentre help me!');
				} else {
					$task["intState"] = 3; // выполнена
					$task["varEnd"] = date('Y-m-d H:i:s');
					$TasksTable->Update($task);
					$this->_log('collect task complete');
					// разброликорать след. задачу
					$TasksTable->unlockNextTask($task['intChildID']);
					$transferTask = $TasksTable->Get(array('intID' => $task['intChildID']));
					$this->_log('open pack task', $transferTask['intID']);
					//Get Articles
					$articles = $packArticlesTable->GetByFields(array('intTaskID' => $transferTask['intID']), null, false);
					//Check 0s
					foreach ($articles as $k => $art) {
						if ($art['intDemandQty'] == 0) {
							unset($articles[$k]);
						}
					}
					$this->_log('pack task articles', $articles);
					//Get From shopID
					$from_shop_id = 0;
					$departmentsAll = $departmentsTable->getList(null, array('varValue'=>'asc'));
					foreach ($departmentsAll as $dep) {
						if ($dep['intVarID'] == $transferTask['intDepartmentID']) {
							$from_shop_id = $dep['intCodeShopSprut'];
							break;
						}
					}
					//Get Order data
					$ord = $OrdersTable->Get(array('Ord_id' => $transferTask['intOrderID']));
					$this->_log('found order', $ord);

					// new scheme - не генерируем накладную перемещения, а генерируем накладную прихода  - для магазина сбора заказа http://task.miritec.com/project/3023/285255/
					$res = $SprutModel->query("select code_trade_hall from mz.shops_wh_vlad where code_shop = ".$ord['Asm_shop_id']);
					if ($res === FALSE){
						$this->_log('paritet_exchange line '.__LINE__.' sql error', $sql);
						//mail('developer@miritec.com', 'paritet_exchange line '.__LINE__.' sql error', $sql);
						mail('developer@bukva.ua', 'paritet_exchange line '.__LINE__.' sql error', $sql);
						return;
					}
					$code_ward = $res[0]["CODE_TRADE_HALL"];

					$number_inv = $SprutModel->ora_function('MZ.GETNUMBERINVOICE', array());
					if ($number_inv === FALSE){
						$this->_log('paritet_exchange line '.__LINE__.' sql error MZ.GETNUMBERINVOICE');
						//mail('developer@miritec.com', 'paritet_exchange line '.__LINE__.' sql error', 'ora_function MZ.GETNUMBERINVOICE');
						mail('developer@bukva.ua', 'paritet_exchange line '.__LINE__.' sql error', 'ora_function MZ.GETNUMBERINVOICE');
						return;
					}

					$COMMENT_TO_INVOICE = iconv('UTF-8', 'Windows-1251', 'Накладная для Интернет-магазина [BUKVAUA_ORDER_NUMBER='.$ord['Ord_id'].'; ETASK_TASK_NUMBER='.$zadacha_sbora_id.';]');
					$sql = "declare
							code_supplier# integer:=6154441;
							code_pattern# integer:= 1499;
							code_shop# integer:=".$ord['Asm_shop_id'].";
							code_warh# integer:=".$code_ward.";
							code_inv# integer:=null;
							number_inv# integer:=".$number_inv.";
							res integer;
							paycalc integer;
							begin
							select MZ.GETCODEINVOICE into code_inv# from dual;

							insert into MZ.INVOICE
							(CODE_INVOICE, CODE_SUBGROUP, CODE_SHOP, NUMBER_INVOICE, CODE_FIRM_DESTINATION, TYPE_SOURCE, CODE_SOURCE, CODE_WAREHOUSE, DATE_INVOICE, VARIETY_INVOICE, TYPE_INVOICE, TYPE_NDS, CODE_ADDITION_SIGN, STATE_INVOICE, COST_DELIVERY, CODE_CURRENCY, PERCENT_CUSTOM, TYPE_DOC_INVOICE, ID_WORKPLACE, CODE_PATTERN, NUMBER_TAX_INVOICE,DATE_TAX_INVOICE,DESCRIPTION,MUSTBE_TAX_INVOICE)
							values
							(code_inv#, 1, code_shop#, number_inv#, 6468101, 'PO', code_supplier#, code_warh#,
							trunc(sysdate), 'O', 'O', '1', -4, 'C', 0, 0, 0, '2', '911', code_pattern#,'P_".$ord['Ord_id']."_".$zadacha_sbora_id."',trunc(sysdate),
							'".$COMMENT_TO_INVOICE."','00');\n
							";

					foreach ($articles as $art) {
						$sql .= "insert into MZ.WARES_INVOICE
								(CODE_INVOICE, CODE_WARES, QUANTITY_BY_INVOICE, CODE_UNIT_BY_INVOICE, PRICE_BY_INVOICE, PRICE_COMING, PERCENT_CUSTOM, AKCIS, ADDITION_1, ADDITION_2, ADDITION_3,vat) values(code_inv#,
								".$art['intArticleID'].",
								".$art['intDemandQty'].",
								19,
								".(($paritet_prods_price[$art['intArticleID']]/1.2)/1.2).",
								".$paritet_prods_price[$art['intArticleID']].",
								0,0,0,0,0,
								(select vat/100 from mz.wares where code_wares=".$art['intArticleID']."));\n";
					}

					// далее изменяем статус накладной на "Оприходована"
					$sql .= "res:= MZ.RECEIPTSINVOICE(code_inv#, paycalc, trunc(sysdate), '2', 0, 106, 'N');\n";
					$sql .= "end;";

					$res = $SprutModel->query($sql);
					if ($res === FALSE){
						$this->_log('paritet_exchange line '.__LINE__.' sql error', $sql);
						//mail('developer@miritec.com', 'paritet_exchange line '.__LINE__.' sql error', $sql);
						mail('developer@bukva.ua', 'paritet_exchange line '.__LINE__.' sql error', $sql);
						return;
					}

					$transferTask["intState"] = 3; // выполнена
					$transferTask["varStart"] = date('Y-m-d H:i:s');
					$transferTask["varEnd"] = date('Y-m-d H:i:s');
					$TasksTable->Update($transferTask);
					$this->_log('done task', $transferTask);
					// разброликорать след. задачу
					$TasksTable->unlockNextTask($transferTask['intChildID']);
					$peremeshenieTask = $TasksTable->Get(array('intID' => $transferTask['intChildID']));
					$this->_log('set transfer task varComment to ', $number_inv);
					$peremeshenieTask["varComment"] = $number_inv;
					$peremeshenieTask["varStart"] = date('Y-m-d H:i:s');
					$peremeshenieTask["intState"] = 2; // в работе чтобы не удалялась
					$TasksTable->Update($peremeshenieTask);

				}
			} else {
				$this->_log('ERROR: task not found', $zadacha_sbora_id);
			}
			// delete files on FTP
			$ParitetExchange->deleteFile($flag);
			$ParitetExchange->deleteFile(str_replace('.flg', '.svb', $flag));

		}
		$this->_log('DONE!');
	}

}

Kernel::ProcessPage(new ParitetExchangeService());
