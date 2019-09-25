<?php

include_once(realpath(dirname(__FILE__)."/classes/variables.php"));

Kernel::Import("classes.web.AdminPage");

class IndexPage extends AdminPage {

	function __construct() {
		Page::__construct("");

		$this->tasksTable = new TasksTable($this->connection);

		$order_id = $this->request->getNumber('intOrderID');
		$cc = $this->tasksTable->getCallCentreForOrder($order_id);
		if ($cc) {
			// задача найдена
			$this->addMessage('Задача колл-центр уже сеществует');

		} else {
			// задачи не найдено, очищаем новые и заблокированые
			$this->tasksTable->clearNewLockedByOrderID($order_id);
			// создаем КЦ(20)
			$cc = $this->tasksTable->generateCallcentreEditTask($order_id, $this->getUserID());
			$this->addMessage('Задача колл-центр успешно создана');
		}
		// редиректим на задачу
		$this->response->redirect('task.php?ID='.$cc);

	}

}

Kernel::ProcessPage(new IndexPage(""));