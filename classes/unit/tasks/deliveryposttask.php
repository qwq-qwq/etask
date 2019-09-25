<?php

Kernel::Import('classes.unit.tasks.DeliveryTask');

class DeliveryPostTask extends DeliveryTask {

	public $final_order_state = 50; // отгружен
	public $mail_template = 'ord_sent_by_ukrpost'; // выслать этот макет письма
	public $addinfo = array('varAdress' => 'Ближайшее отделение Укрпочты');

}