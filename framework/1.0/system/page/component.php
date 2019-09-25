<?php

if (!defined('EVENT_FUNCTION_PREFIX')) {
	define('EVENT_FUNCTION_PREFIX', 'on');
}

class Component {

	function processComponent() {
		$this->authenticate();
		$this->index();
		$this->processEvents();
		$this->render();
	}

	function index() {}

	function render() {}

	function processEvent($eventName) {
		$methodHandlerName = EVENT_FUNCTION_PREFIX.$eventName;
		if( method_exists($this, $methodHandlerName)) {
			$this->$methodHandlerName();
		} //var_dump(get_class($this));die();
	}

	function getResponse() {}

}
