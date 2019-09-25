<?php

Kernel::Import('system.response.AbstractResponse');

class XmlResponse extends AbstractResponse{

	function XmlResponse(&$page, &$document) {
		parent::AbstractResponse($page, $document);
	}

	function displayHeader() {
		header('Content-type: text/xml; charset='.PROJECT_CHARSET);
	}

	function displayContent() {
		$xml  = "<?xml version='1.0' encoding='".PROJECT_CHARSET."'?>\n";
		$xml .= $this->document->toXml();
		echo $xml;
	}
}

?>