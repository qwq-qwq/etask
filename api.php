<?php

include_once(realpath(dirname(__FILE__)."/classes/variables.php"));

Kernel::Import('system.db.mysql.*');
Kernel::Import("classes.data.etasks.taskstable");
Kernel::Import("classes.data.etasks.deliveryarticlestable");

class API10 {

	private $connection;
	private $key = 'preA1phaVer$ion';
	private $tasksTable;
	private $DeliveryArticlesTable;

	function __construct($id, $key, $action) {
		if (empty($action)) $action = 'CreateCallcentreTask';
		if (md5($this->key.$id) === $key) {
			if (method_exists($this, $action)) {
				$this->openConnection();
				$this->$action($id);
			} else {
				die('ACTION_ERROR');
			}
		} else {
			die('HASH_ERROR');
		}
	}
	
	function CreateCallcentreTask($id) {
		$this->tasksTable = new TasksTable($this->connection);
		$this->tasksTable->generateCallcentreEditTask($id, 0, 10);
		die('OK');
	}
	
	function DeliveryDone($id) {
		$this->tasksTable = new TasksTable($this->connection);
		$this->DeliveryArticlesTable = new DeliveryArticlesTable($this->connection);
		$task = $this->tasksTable->GetOrderDeliveryTask($id);
		if (count($task) > 0) {
			//Update delivery articles
			$goods = $this->DeliveryArticlesTable->GetByFields(array('intTaskID' => $task['intID']), null, false);
			foreach ($goods as $article) {
				$article['intDoneQty'] = $article['intDemandQty'];
				$this->DeliveryArticlesTable->Update($article);
			}
			//Update task
			$task['intState'] = 3;
			$task['varEnd'] = date('Y-m-d H:i:s');
			$this->tasksTable->Update($task);
			die('OK');
		}
		die('TASK NOT FOUND');
	}
	
	function DeleteCallcentreTask($id) {
		$this->tasksTable = new TasksTable($this->connection);
		$data = array('intOrderID' => $id, 'intType' => 10);
		$task = $this->tasksTable->DeleteByFields($data);
		die('OK');
	}

	function openConnection() {
		$this->connection = new MySQLConnection( MySQLConnectionProperties::createByURI(DB_URI) );
		$this->connection->properties->setEncoding( DB_CHARSET_UTF8 );
		$this->connection->Open();
	}

	function __destruct() {
		if (is_object($this->connection)) $this->connection->Close();
	}

}

$api10 = new API10($_GET['id'], $_GET['key'], $_GET['action']);
