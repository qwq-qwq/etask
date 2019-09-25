<?php

Kernel::Import('system.mail.sendmail');

class SendMailMessage {

	function SendMailMessage($to, $mailmessage) {
		return new SendMail($to, $mailmessage->getBody(), $mailmessage->getSubject(), $mailmessage->getHeaders(), $mailmessage->getAttachment(), $mailmessage->getAttachmentEncoding());
	}

}
?>