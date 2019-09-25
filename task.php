<?php

include_once(realpath(dirname(__FILE__)."/classes/variables.php"));

Kernel::Import("classes.web.AdminPage");

class IndexPage extends AdminPage
{
	private $task = null;	
	
	function index() {
		parent::index();		
		$ID = $this->request->getNumber('ID');
		$this->task = $this->getTaskInstance($ID);
		$this->task->processEvent();
	}

	function render() {
		parent::render();
		$this->task->render();
	}	
}

Kernel::ProcessPage(new IndexPage("task.tpl"));