<?php
define('TASK_MODE_EDIT', 1);
define('TASK_MODE_READONLY', 2);

Kernel::Import("classes.data.sprut.SprutModel");
Kernel::Import('classes.data.empik.AccountsTable');
Kernel::Import('classes.data.empik.OrdersTable');
Kernel::Import('classes.data.empik.SalesTable');
Kernel::Import('classes.data.empik.CountriesTable');
Kernel::Import('classes.data.empik.CitiesTable');
Kernel::Import('classes.data.empik.DeliveryTypesTable');
Kernel::Import('classes.data.empik.WhsTable');
Kernel::Import('classes.data.empik.PaymentTypesTable');
Kernel::Import('classes.data.empik.GmapTable');
Kernel::Import('classes.data.empik.EansTable');
Kernel::Import('classes.data.empik.MailsTemplatesTable');
Kernel::Import('classes.data.empik.CatalogAggregationTable');
Kernel::Import('classes.data.etasks.CommentsTable');
Kernel::Import('classes.data.etasks.DocumentsTable');
Kernel::Import('classes.data.etasks.TaskTypesTable');
Kernel::Import('classes.data.etasks.BillTable');
Kernel::Import('classes.data.etasks.TaskStateTable');

Kernel::Import("system.mail.*");

class Task {

	protected $mode = TASK_MODE_EDIT;

	/***
	 * @var AdminPage
	 */
	protected $page;
	protected $template;
	protected $task_data;
	protected $order_data = array();

	/**
	 * @var taskTypesTable
	 * @see classes/data/taskTypesTable.php
	 */
	protected $taskTypesTable;

	protected $accountsTable;
	protected $ordersTable;
	protected $salesTable;
	protected $countriesTable;
	protected $citiesTable;
	protected $deliveryTypesTable;
	protected $whsTable;
	protected $paymentTypesTable;
	protected $gmapTable;
	protected $mailTemplatesTable;
	protected $commentsTable;
	protected $documentsTable;
	protected $sprutModel;
	protected $taskStateTable;

	protected $exec_time = 1800;

	// Состояния оплаты
	protected $paymentState = array(0=>'Не оплачен', 1=>'Оплачен', 2=>'Ожидает оплаты');

	function __construct(&$page, $data) {
		$this->page = $page;
		$this->task_data = $data;

		/**
		 * Если пользователь, который пытается открыть задачу (открывается по ID)
		 * не имеет доступа к департаменту, которому назначена задача
		 * отправлять его на 404
		 */
		if (empty($this->task_data) || ! in_array($this->task_data['intDepartmentID'], $this->page->getUserDepartmentsIDs())) {
			$this->page->getResponse()->redirect('error.php?event=404');
		}

		/**
		 * Если задача не 1 - "новая", а пользователь != исполнителю задачи,
		 * ИЛИ задача в состоянии 3,4,5
		 * ТОГДА открывать её в режиме для чтения.
		 */
		if ( ($this->task_data['intState'] != 1 && $this->task_data['intExecutorID'] != $this->page->getUserID()) || in_array($this->task_data['intState'], array(5, 3, 4))) {
			$this->mode = TASK_MODE_READONLY;
		}

		/**
		 * Initialize Data Tables
		 */
		$this->accountsTable = new AccountsTable($this->page->getConnectionEmpik());
		$this->ordersTable = new OrdersTable($this->page->getConnectionEmpik());
		$this->salesTable = new SalesTable($this->page->getConnectionEmpik());
		$this->countriesTable = new CountriesTable($this->page->getConnectionEmpik());
		$this->citiesTable = new CitiesTable($this->page->getConnectionEmpik());
		$this->deliveryTypesTable = new DeliveryTypesTable($this->page->getConnectionEmpik());
		$this->whsTable = new WhsTable($this->page->getConnectionEmpik());
		$this->paymentTypesTable = new PaymentTypesTable($this->page->getConnectionEmpik());
		$this->gmapTable = new GmapTable($this->page->getConnectionEmpik());
		$this->mailTemplatesTable = new MailTemplatesTable($this->page->getConnectionEmpik());
		$this->commentsTable = new CommentsTable($this->page->getConnection());
		$this->documentsTable = new DocumentsTable($this->page->getConnection());
		$this->sprutModel = new SprutModel(DB_SPRUT_CONNECT_PARAMS);
		$this->taskTypesTable = new TaskTypesTable($this->page->getConnection());
		$this->billTable = new BillTable($this->page->getConnection());
		$this->taskStateTable = new TaskStateTable($this->page->getConnection());

		// get order data
		$this->order_data = $this->ordersTable->get(array('Ord_id'=>$this->task_data['intOrderID']));
	}

	/**
	 * Взять задачу на исполнение
	 * Меняет статус задачи на "в работе", выставляет дату старта задачи,
	 * выставляет исполнителя (тот, кто нажал).
	 */
	function OnSetExecutor() {
		$this->task_data['intState'] = 2; // в работе
		$this->task_data['intExecutorID'] = $this->page->getUserID();
		$this->task_data['varStart'] = date("Y-m-d H:i:s");
		$this->task_data['intExecutionTime'] = $this->exec_time;
		if($this->task_data['intType']>=90 and $this->task_data['intType']<=120){
			$num = $this->page->tasksTable->GetTaskOrder($this->task_data['intID']);
			for($i=1; $i<=count($num); $i++){
				$data = array('intOrderID'=>$this->task_data['intOrderID'],'varTime'=>date('Y-m-d H:i:s'));
				$this->billTable->insert($data);
			}
		}
		$this->page->tasksTable->update($this->task_data);
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}
	/**
	 * When the Task is done
	 *
	 */
	function OnPerformed() {
		if ($this->task_data['intType']==100){
			$smarty = new Smarty();
			$smarty->template_dir = TEMPLATES_PATH.'admin/mail/';
			$smarty->compile_dir = PROJECT_CACHE.'smarty/';
			$smarty->config_dir = TEMPLATES_PATH.'admin/mail/';
			$smarty->cache_dir = PROJECT_CACHE.'smarty/';
			$smarty->caching = false;
			$smarty->debugging = ENABLE_INTERNAL_DEBUG;
			
			$shops = $this->gmapTable->getlist(array('sprut_code'=>$this->order_data['Asm_shop_id']));
			$taskstate = $this->taskStateTable->getlist(array('intID'=>$this->order_data['intState']));
			
			$data = array_merge($this->task_data, $this->order_data);
			
			$data['Shop_name'] = strip_tags($shops[0]['name_ru']);
			$data['description_ru'] = strip_tags($shops[0]['description_ru']);
			$data['varState'] = $taskstate[0]['varName'];
			
			$smarty->assign('data', $data);
			$contentdata = $smarty->fetch('report.tpl');

			$msg = new MailMessage();
			$msg->setFrom('Bukva shop <noreply@bukva.ua>');
			$msg->setSubject('Уведомление о доставке курьером '.date('d.m.Y H:i:s'));
			$filename = '/tmp/report_'.date('d.m.Y H:i:s').'.xls';
			file_put_contents($filename, $contentdata);
			$msg->setAttachment($filename);
			new SendMailMessage('delivery@bukva.ua', $msg);
			//new SendMailMessage('vadim.titov@miritec.com', $msg);
			@unlink($filename);
		}
		$this->task_data['intState'] = 3; // done successfully
		$this->task_data['varEnd'] = date("Y-m-d H:i:s");
		$this->page->tasksTable->update($this->task_data);
		$this->page->getResponse()->redirect('index.php');
	}
	/**
	 * Скачать документ
	 */
	function OnDocumentDownload() {
		$data['intDocumentID'] = $this->page->getRequest()->getNumber('documents_intDocumentID');
		$data = $this->documentsTable->Get($data);
		if ( ! empty($data)) {
			$filename = $data['varFilename'];
			$filepath = FILESTORAGE. "docs/" .substr($data['varFile'],0,3)."/".$data['varFile'];
			$size = filesize($filepath);
			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
				header('Content-Type: "application/octet-stream"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header("Content-Transfer-Encoding: binary");
				header('Pragma: public');
				header("Content-Length: ".$size);
			} else {
				header('Content-Type: "application/octet-stream"');
				header('Content-Disposition: attachment; filename="'.$filename.'"');
				header("Content-Transfer-Encoding: binary");
				header('Expires: 0');
				header('Pragma: no-cache');
				header("Content-Length: ".$size);
			}
			readfile($filepath);
			$this->page->terminatePage();
		}
	}

	/**
	 * Добавить к задаче комментарий
	 */
	function OnAddComment() {
		$data = null;
		$commentText = $this->page->getRequest()->getString('comments_varText');
		if ( $text = trim($commentText)) {
			$data['intTaskID'] = $this->task_data['intID'];
			$data['intUserID'] = $this->page->getUserID();
			$data['varCreated'] = date("Y-m-d H:i:s");
			$data['varText'] = $text;
			$data['intCommentID'] = $this->commentsTable->insert($data);
		}
		if ( ! empty($data)) {
			$files = $this->page->getRequest()->getFiles('fileComment');
			if ( ! empty($files)) {
				foreach ($files['name'] as $k => $filename) {
					$source = array('name'=>$filename,
									'tmp_name'=>$files['tmp_name'][$k],
									'size'=>$files['size'][$k],
									'type'=>$files['type'][$k],
									'error'=>$files['error'][$k]);
					$this->saveFileForComment($data['intCommentID'], $source);
				}
			}
		}
		$this->page->getResponse()->redirect('task.php?ID='.$this->task_data['intID']);
	}

	/**
	 * Сохранение загруженного файла
	 * @param $intCommentID
	 * @param $source
	 */
	function saveFileForComment($intCommentID, $source) {
		$file = md5(time().rand(1000,9999));
		$dir = FILESTORAGE. "docs/" .substr($file, 0, 3)."/";
		if ($source["size"]) {
			if ( ! is_dir($dir)){
				if ( ! mkdir($dir, 0777)){
					$this->page->addErrorMessage('Не удалось создать директорию для загрузки файла');
				}
			}
			$filepath = $dir.$file;
			if ( ! copy($source['tmp_name'], $filepath)){
				$this->page->addErrorMessage('Не удалось создать директорию для загрузки файла');
			}
			$data = array();
			$data['varTableName'] = DB_TABLE_COMMENTS;
			$data['intIdentID'] = $intCommentID;
			$data['varCreated'] = date("Y-m-d H:i:s");
			$data['intUserID'] = $this->page->getUserID();
			$data['varFilename'] = $source['name'];
			$data['intSize'] = $source['size'];
			$data['varType'] = $source['type'];
			$data['varFile'] = $file;
			//echo '<pre>'; print_r($data); echo '</pre>';
			$this->documentsTable->Insert($data);
		}
	}

	/**
	 * render
	 */
	function render() {
		if (empty($this->template)) {
			echo 'You are using abstract controller';
			$this->page->terminatePage();
		} else {
			$this->page->GetDocument()->addValue('task', $this->task_data);
			$this->page->GetDocument()->addValue('mode', $this->mode);
			$users = $this->page->getAllUsersFio();
			$this->page->getDocument()->addValue('exec_user', $users[$this->task_data['intExecutorID']]);
			$this->page->GetDocument()->addValue('shop_url', SHOP_URL);
			$this->page->GetDocument()->addValue('admin_comment_text', $this->order_data['Adm_comment']);
			$this->page->GetDocument()->addValue('user_comment_text', $this->order_data['Ord_comment']);
		}
		// pagetitle
		$title = "Задача №".$this->task_data['intID']." (по заказу №".$this->task_data['intOrderID']." от ".$this->order_data['Ord_date'].") Заказчик: ".$this->order_data['Contact_name'];
		$this->page->GetDocument()->addValue('pagetitle', $title);
	}

	/**
	 * Подготовка списка комментариев
	 */
	function prepareComments() {
		$data = array('intTaskID'=>$this->task_data['intID']);
		$sortBy = $this->page->GetRequest()->getString('comments_sortBy', null, 'varCreated');
		$sortOrder = $this->page->GetRequest()->getNumber('comments_sortOrder', null, 1);
		$field = str_replace('comments_','',$sortBy);
		$sort[$field] = empty($sortOrder) ? 'ASC' : 'DESC';
		$page = $this->page->GetRequest()->getNumber('comments_page', NULL, 1);
		$comments = $this->commentsTable->GetList($data, $sort, null, null, null, true, $page, 5);
		// add documents to comments
		$data = array('intTableName'=>DB_TABLE_DOCUMENTS);
		$ulist = $this->page->getAllUsersFio();
		foreach ($comments as $k => $comment) {
			$data['intIdentID'] = $comment['intCommentID'];
			$comments[$k]['userName'] = $ulist[$comment['intUserID']];
			$comments[$k]['documents'] = $this->documentsTable->getList($data);
		}
		$this->page->GetDocument()->addValue('filestorage_url', FILESTORAGE_URL);
		$this->page->GetDocument()->addValue('comments_sortBy', $sortBy);
		$this->page->GetDocument()->addValue('comments_sortOrder', $sortOrder);
		$this->page->GetDocument()->addValue('comments', $comments);
	}

	function processEvent() {
		$eventName = $this->page->getRequest()->Value(EVENT_FUNCTION_TAG);
		if ($this->mode == TASK_MODE_READONLY && $eventName != 'UnlockTask') return;
		$methodHandlerName = EVENT_FUNCTION_PREFIX.$eventName;
		if( method_exists($this, $methodHandlerName)) {
			$this->$methodHandlerName();
		}
	}

}