<?php
include_once(realpath(dirname(__FILE__)."/../classes/variables.php"));

Kernel::Import("system.page.Page");
Kernel::Import('classes.html_mime_mail.mail');

Class HourlyPackDoneReport extends Page {


	function __construct() {
		parent::__construct('void.tpl');

		/*
		ВЫПОЛНЕННЫЕ  задачи  УПАКОВКИ за предыдущий час, где тип доставки заказа – САМОВЫВОЗ.
		*/
		$SQL = "SELECT
				e_t.intOrderID,
				DATE_FORMAT(m_o.Ord_date, '%H:%i %d.%m.%Y') as Ord_date,
				DATE_FORMAT(e_t.varEnd, '%H:%i %d.%m.%Y') as varEnd,
				m_o.Contact_name,
				m_o.Contact_phone,
				m_o.Contact_mail,
				(select m_g.name_ru from prod_bukva_ua.gmap as m_g where m_o.Shop_id=m_g.sprut_code) as pack_shop,
				(select m_s.Name_RU from prod_bukva_ua.cities as m_s, prod_bukva_ua.gmap as m_g  where m_o.Shop_id=m_g.sprut_code and m_s.City_id=m_g.city_id) as pack_city,
				m_o.Cost
				FROM  etask.tasks as e_t
				LEFT JOIN prod_bukva_ua.orders as m_o ON (m_o.Ord_id=e_t.intOrderID)
				WHERE  e_t.intType =90
				AND  e_t.intState =3
				and e_t.varEnd>=DATE_SUB( DATE_FORMAT(NOW(),'%Y-%m-%d %H:00:00') ,INTERVAL 1 HOUR)
				and e_t.varEnd<=DATE_SUB( DATE_FORMAT(NOW(),'%Y-%m-%d %H:59:59'),INTERVAL 1 HOUR)
				ORDER BY  pack_shop ASC, e_t.varEnd DESC";
		$rows = $this->connection->ExecuteScalar($SQL, false);
		if (count($rows)) {
				$xsl = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>';
				$xsl .= '<table style="border-collapse: collapse;" border=1>';
				$xsl .= '<tr bgcolor=silver>';
				$xsl .= '<td>Номер</td>';
				$xsl .= '<td>Заказ на сумму</td>';
				$xsl .= '<td>Дата создания заказа</td>';
				$xsl .= '<td>Дата упаковки заказа</td>';
				$xsl .= '<td>ФИО клиента</td>';
				$xsl .= '<td>Телефон клиента</td>';
				$xsl .= '<td>Е-мейл клиента</td>';
				$xsl .= '<td>Магазин упаковки</td>';
				$xsl .= '<td>Город в котором находится магазин</td>';
				$xsl .= '</tr>';
				foreach ($rows as $row) {
					$xsl .= '<tr>';
					$xsl .= '<td>'.$row['intOrderID'].'</td>';
					$xsl .= '<td>'.$row['Cost'].'</td>';
					$xsl .= '<td>'.$row['Ord_date'].'</td>';
					$xsl .= '<td>'.$row['varEnd'].'</td>';
					$xsl .= '<td>'.$row['Contact_name'].'</td>';
					$xsl .= '<td>\''.$row['Contact_phone'].'</td>';
					$xsl .= '<td>'.$row['Contact_mail'].'</td>';
					$xsl .= '<td>'.$row['pack_shop'].'</td>';
					$xsl .= '<td>'.$row['pack_city'].'</td>';
					$xsl .= '</tr>';
				}
				$xsl .= '</table>';
				$xsl .= '</body></html>';

				$filename = PROJECT_PATH.'tmp/'.'HourlyPackDoneReport'.date('YmdHi').'.xls';
				$handle = fopen($filename, 'w');
				fwrite($handle, $xsl);
				fclose($handle);

				$msg = new htmlMimeMail();
				$msg->setTextCharset(PROJECT_CHARSET);
				$msg->setSubject('Выполненные задачи упаковки самовывоза за предыдущий час');
				$msg->setText('Выполненные задачи упаковки самовывоза за предыдущий час');
				$msg->setFrom(PROJECT_FROM_MAIL);
				$msg->addAttachment($msg->getFile($filename), basename($filename));
				$message = $msg->send(array('contact@bukva.ua'));

		} else {
			$message = 'nothing to send';
		}

		echo $message;
		$this->terminatePage();
	}

}

Kernel::ProcessPage(new HourlyPackDoneReport());