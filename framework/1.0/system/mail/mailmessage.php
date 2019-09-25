<?php

if (!defined('MAIL_TEMPLATES_PATH')) {
	define('MAIL_TEMPLATES_PATH', TEMPLATES_PATH.'mail/');
}

class MailMessage {

	var $headers = array();
	var $body = '';
	var $subject = '';
	var $encoding = 'uft-8';
	var $content_type = 'text/html';
	var $attachment = null;
	var $attachmentEncoding = 'application/octet-stream';

	function MailMessage() {
	}

	function getHeaders() {
		$this->setHeader('Content-type', $this->content_type."; charset=\"".$this->encoding."\"");
		return $this->headers;
	}

	function getAttachment() {
		return $this->attachment;
	}

	function setAttachment($path2file) {
		$this->attachment = $path2file;
	}

	function setAttachmentEncoding($enc) {
		$this->attachmentEncoding = $enc;
	}

	function getAttachmentEncoding() {
		return $this->attachmentEncoding;
	}

	function setFrom($from) {
		$this->setHeader('From', $from);
	}

	function setHeader($head, $data) {
		$this->headers[$head] = trim($data);
	}

	function setBody($txt) {
		$this->body = $txt;
	}

	function setSubject($txt) {
		$this->subject = trim($txt);
	}

	function getSubject() {
		return $this->subject;
	}
	
	function _mb_substr_by_str($source, $start_str, $end_str = '', $inner = false){
		$ret = '';
		$l_offset = 0;
		$r_offset = 0;
		if($inner){
			$l_offset = mb_strlen($start_str);
		}
		$st = mb_strpos($source,$start_str);
		if($st !== false){
			$st += $l_offset;
			$fin = false;
			if(mb_strlen($end_str) > 0) $fin = mb_strpos($source, $end_str, $st + 1);
			if($fin !== false){
				if(!$inner) $r_offset = mb_strlen($end_str);
				$ret = mb_substr($source, $st, $fin - $st + $r_offset);
			}else{
				$ret = mb_substr($source, $st);
			}
		}
		return $ret;
	}

	function renderBodyFromTemplate($filename, $data) {
		$rawBody = file_get_contents(MAIL_TEMPLATES_PATH.$filename);
		if (is_array($data)) {
			foreach ($data as $key=>$val) {
				$rawBody = str_replace('{$'.$key.'}', $val, $rawBody);
				
				$varyText = $this->_mb_substr_by_str($rawBody, '{if $'.$key.'}', '{/if}');
				if ($val) $custText = $this->_mb_substr_by_str($varyText, '{if $'.$key.'}', '{else}', true);
				else $custText = $this->_mb_substr_by_str($varyText, '{else}', '{/if}', true);
				$rawBody = str_replace($varyText, $custText, $rawBody);
			}
		}
		
		$rawBody = explode("\n", $rawBody);
		if (strpos($rawBody[0], 'Subject: ') === 0) {
			$this->setSubject(str_replace('Subject: ', '', $rawBody[0]));
			unset($rawBody[0]);
		}
		$rawBody = implode("\n", $rawBody);
		$this->setBody($rawBody);
	}

	function getBody() {
		return $this->body;
	}

}
?>