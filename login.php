<?php
include_once(dirname(__FILE__)."/classes/variables.php");

Kernel::Import("classes.web.PublicPage");

class IndexPage extends PublicPage {

	function index() {
		parent::index();
	}

	function OnLogon(){
		$this->OnAutologin();
	}	

	function render() {
		parent::render();		
	}
}

Kernel::ProcessPage(new IndexPage("logon.tpl"));
