<?php

Kernel::Import('classes.html_mime_mail.mail');

class SendMail {

	function SendMail($to, $html, $subject, $headers, $attachment = null, $encoding = 'application/octet-stream') {
		$enc = 'base64';
		if ($encoding == 'text/xml') $enc = '7bit';
		// init mime sender
		$mimeMail = new htmlMimeMail();
//		$imgurl = FILESTORAGE_URL.'mail/';
//		$imgpath = FILESTORAGE.'mail';
//		$html = str_replace('src="'.$imgurl, 'src="', $html);
//		$html = str_replace('background="'.$imgurl, 'background="', $html);
		// tune settings
		$mimeMail->setTextCharset('utf-8');
		$mimeMail->setHtmlCharset('utf-8');
//		$mimeMail->setHtml($html, null, $imgpath.'/');
		$mimeMail->setHtml($html);
		$mimeMail->setFrom($headers['From']);
		$mimeMail->setSubject($subject);
		// include attachments
		if (!is_null($attachment)) {
//			var_dump($mimeMail->getFile($attachment), basename($attachment), $encoding, $enc);
			$mimeMail->addAttachment($mimeMail->getFile($attachment), basename($attachment), $encoding, $enc);
		}

		$result = $mimeMail->send(array($to));
		unset($mimeMail);
		return $result;
	}

}

?>