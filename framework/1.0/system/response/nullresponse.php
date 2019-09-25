<?php
/**
 * Null response, just a stub
 *
 * @package system
 */
Kernel::Import('system.response.AbstractResponse');

class NullResponse extends AbstractResponse{

	function NullResponse(&$page, &$document) {
		parent::AbstractResponse($page, $document);
	}

	function displayContent() {
		return '';
	}
}

?>