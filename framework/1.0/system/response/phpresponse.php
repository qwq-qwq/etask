<?php

Kernel::Import('system.response.AbstractResponse');

class PHPResponse extends AbstractResponse {

	var $maintemplate;
	var $content_type = 'text/html';

	function __construct(&$page, &$document, $maintemplate = 'layout.php') {
		parent::AbstractResponse($page, $document);
		$this->maintemplate = $maintemplate;
	}

	function displayHeader() {
		header('Content-type: '.$this->content_type.'; charset='.PROJECT_CHARSET);
	}

	function displayContent() {
		$template_dir = TEMPLATES_PATH.$this->page->getTemplatesRoot();
		//extract variables
		$data = array();
		foreach ( $this->document->childs as $dataObject ) {
			$data[$dataObject->name] = $dataObject->value;
		}
		extract($data);
		//render page
		ob_start();
		include($template_dir.$this->page->Template);
		$page = ob_get_contents();
		@ob_end_clean();
		//extract page
		extract(array('page' => $page));
		//Final render
		include($template_dir.$this->maintemplate);
	}
}
