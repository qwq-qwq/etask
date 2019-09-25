<?php

Kernel::Import('system.response.AbstractResponse');

class XsltResponse extends AbstractResponse{

	function XsltResponse(&$page, &$document) {
		parent::AbstractResponse($page, $document);
	}

	function displayHeader() {
		header('Content-type: text/html; charset='.PROJECT_CHARSET);
	}

	function displayContent() {
		$xslt = TEMPLATES_PATH.$this->page->getTemplatesRoot().$this->page->Template;
		if (!file_exists($xslt)) Kernel::RaiseError("Template file '".$xslt."' does not exist");
		$xslt_file_data = file($xslt);
		$xslt_data = implode("", $xslt_file_data);
		$xslt_data = preg_replace ("/<xsl:include href=\"([^\"]*)\"\/>/", "<xsl:include href=\"file://" . dirname($xslt) . "/\\1\"/>", $xslt_data);
		$xml = $this->document->toXml();
		$arg = array('/_xml' => $xml, '/_xsl' => $xslt_data);
		$xh = xslt_create();
		$xhtml = xslt_process($xh, 'arg:/_xml', 'arg:/_xsl', NULL, $arg);
		xslt_free($xh);
		$xhtml = str_replace("<?xml version=\"1.0\" encoding=\"".PROJECT_CHARSET."\"?>", "", $xhtml);
		$xhtml = str_replace("&amp;", "&", $xhtml);
		echo $xhtml;
	}
}

?>