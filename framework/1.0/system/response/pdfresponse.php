<?php

Kernel::Import('system.response.AbstractResponse');

class PDFResponse extends AbstractResponse {

	var $maintemplate;

	function __construct(&$page, &$document, $maintemplate = 'layout.php') {
		parent::AbstractResponse($page, $document);
		$this->maintemplate = $maintemplate;
	}

	function displayHeader() {
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Pragma: private");
   		header("Content-type: application/pdf");
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
		ob_start();
		include($template_dir.$this->maintemplate);
		$content = ob_get_contents();
		@ob_end_clean();
		//echo $content;die;
		/////////////////
		if (empty($fileName)) {
			$fileName = md5(date('YmdHi').microtime().rand(0, 9999));
		}

	    $htmlfilename = PROJECT_PATH.'tmp/'.$fileName.".html";
	    $pdffilename = PROJECT_PATH.'tmp/p'.$fileName.".pdf";
	    // rec html doc
	    $fp = fopen($htmlfilename, 'w');
	    $content = str_replace('№', '#', $content);
	    fwrite($fp, iconv("UTF-8", "windows-1251", $content));
	    fclose($fp);
	    // generate pdf
	    exec("/usr/local/bin/htmldoc --charset cp-1251 --format pdf --textfont serif --fontsize 7 --webpage ".$htmlfilename." --outfile ".$pdffilename);
	
	    header("Content-Disposition: attachment; filename=\"{$fileName}.pdf\"");
	    readfile($pdffilename);
	    @unlink($htmlfilename);
	    @unlink($pdffilename);
	}
}