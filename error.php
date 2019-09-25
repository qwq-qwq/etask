<?php
include_once(dirname(__FILE__)."/classes/variables.php");

Kernel::Import("classes.web.AdminPage");

class IndexPage extends AdminPage {

	function index() {
		parent::index();
	}	

	function On404() {
		$this->setPageTitle('Error 404');
		$this->Template = "errors/404.tpl";
	}
	
	function render() {
		parent::render();		
	}
}

Kernel::ProcessPage(new IndexPage("void.tpl"));
