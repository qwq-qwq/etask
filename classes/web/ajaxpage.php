<?php

Kernel::Import("system.page.Page");
Kernel::Import('system.response.SmartyResponse');

class AjaxPage extends Page {
	
	function __construct($Template) {
		parent::__construct($Template);
		$this->setResponse(new SmartyResponse($this, $this->document));
		$this->response->maintemplate = "layout_ajax.tpl";
				
	}
	
	function authenticate() {
		$intUserID = $this->getUserID();
		if ( empty($intUserID)) {
			$this->terminatePage();
		}
	}	
	
	function getUserID () {
		$user = $this->session->get('USER_DATA');
		return $user['intUserID'];
	}
	
	function getTemplatesRoot() {
		return "admin/";
	}

	function getSessionID()	{
		return PROJECT_SESSION_NAME . 'admin';
	}

	function render() {		
	}

}