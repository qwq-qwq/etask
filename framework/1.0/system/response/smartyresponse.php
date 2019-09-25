<?php

Kernel::Import('system.response.AbstractResponse');
Kernel::Import('system.smarty.smarty');

class SmartyResponse extends AbstractResponse {

	var $maintemplate;
	var $content_type = 'text/html';

	function SmartyResponse(&$page, &$document, $maintemplate = 'layout.tpl') {
		parent::AbstractResponse($page, $document);
		$this->maintemplate = $maintemplate;
	}

	function displayHeader() {
		header('Content-type: '.$this->content_type.'; charset='.PROJECT_CHARSET);
	}

	function displayContent() {
		$smarty = new Smarty();
		$smarty->template_dir = TEMPLATES_PATH.$this->page->getTemplatesRoot();
		$smarty->compile_dir = PROJECT_CACHE.'smarty/';
		$smarty->config_dir = TEMPLATES_PATH.$this->page->getTemplatesRoot();
		$smarty->cache_dir = PROJECT_CACHE.'smarty/';
		$smarty->caching = (int)ENABLE_TEMPLATES_CACHE;
		$smarty->compile_id = $this->page->getTemplatesRoot();
		$smarty->cache_lifetime = 3600;
		$smarty->debugging = ENABLE_INTERNAL_DEBUG;
		$smarty->assign('page', TEMPLATES_PATH.$this->page->getTemplatesRoot().$this->page->Template);
		foreach ( $this->document->childs as $dataObject ) {
			$smarty->assign($dataObject->name, $dataObject->value);
		}
		$smarty->assign('page_loaded_in', microtime(true) - SCRIPT_START_TIME);
		echo $smarty->fetch(TEMPLATES_PATH.$this->page->getTemplatesRoot().$this->maintemplate);
	}
}
