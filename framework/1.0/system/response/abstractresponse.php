<?php
class AbstractResponse {

	/**
	 * Page object
	 *
	 * @var Page
	 */
	var $page;
	/**
	 * Document object
	 *
	 * @var DataLayer
	 */
	var $document;

	function AbstractResponse(&$page, &$document) {
		$this->page =& $page;
		$this->document =& $document;
	}

	function displayHeader() {
	}

	function displayContent() {
	}

	function display() {
		$this->displayHeader();
		$this->displayContent();
	}

	function redirect($URL) {
		$this->page->terminatePage(false);
		header('Location: '.$URL);
		die();
	}

}
?>