<?php

Kernel::Import("classes.web.AdminPage");

class PublicPage extends AdminPage {

	function __construct($Template) {
		parent::__construct($Template);
	}
	
	function authenticate() { }

	function render() {
		parent::render();
	}
	
}