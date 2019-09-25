<?php

Class ParitetExchange {

	public $sprutModel;
	public $_ftp_connected = false;

	function __construct(&$sprutModel) {
		$this->sprutModel = &$sprutModel;
		$this->connectFTP();
	}

	function getFileName($order_id, $task_id) {
		return 'etask_'.$order_id.'_'.$task_id;
	}

	function putFile($order, $task, $articles) {
		// get name
		$filename = $this->getFileName($order["Ord_id"], $task['intID']);
		// create header
		$text = '"101";"'.$task['intID'].'";"'.$task["varCreation"].'";"1B4022N0000000";""'."\n";
		// add products
		$article_ids = array();
		$article_filled = array();
		if (is_array($articles)) {
			foreach ($articles as $article) {
				$article_ids[$article["intArticleID"]] = true;
				$article_filled[$article["intArticleID"]] = $article;
			}
		}
		$suppliers = $this->sprutModel->GetProductSupplierData($article_ids);
		foreach ($article_filled as $article) {
			$text .= '"0";"101";"'.$suppliers[$article['intArticleID']]['ARTICL_SUPPLIER'].'";"'.$article["intDemandQty"].'";"'.$suppliers[$article['intArticleID']]['PRICE_SUPPLIER'].'"'."\n";
		}

		// creat files
		$fp = fopen(PROJECT_PATH.'tmp/'.$filename.'.svb', 'w');
		fwrite($fp, $text);
		fclose($fp);
		touch(PROJECT_PATH.'tmp/'.$filename.'.flg');
		// ftp put file
		if (ftp_put($this->conn_id, '/orders/'.$filename.'.svb', PROJECT_PATH.'tmp/'.$filename.'.svb', FTP_ASCII)) {
			// do nothing
		} else {
			die("There was a problem while uploading $filename.svb");
		}
		if (ftp_put($this->conn_id, '/orders/'.$filename.'.flg', PROJECT_PATH.'tmp/'.$filename.'.flg', FTP_ASCII)) {
			// do nothing
		} else {
			die("There was a problem while uploading $filename.flg");
		}

		// clean files
		unlink(PROJECT_PATH.'tmp/'.$filename.'.svb');
		unlink(PROJECT_PATH.'tmp/'.$filename.'.flg');

	}

	function connectFTP() {
		if (!$this->_ftp_connected) {
			$this->conn_id = ftp_connect(PARITET_FTP_HOST) or die("Couldn't connect to ".PARITET_FTP_HOST); ;
			if (ftp_login($this->conn_id, PARITET_FTP_USER, PARITET_FTP_PASSWD)) {
				$this->_ftp_connected = true;
			} else {
				echo "Couldn't connect as ".PARITET_FTP_USER;
			}
		}
	}

	function getFilesList($dir) {
		$this->connectFTP();

		$contents = ftp_nlist($this->conn_id, $dir);

		$ret = array();
		if (is_array($contents)) {
			foreach ($contents as $dir) {
				if (preg_match('/^std_84n(.+)\.flg$/', basename($dir))) $ret[] = $dir; // get flag files only
			}
		}
		return $ret;
	}

	function getFile($src) {
		$this->connectFTP();

		$tempHandle = fopen('php://temp', 'r+');

    	//Get file from FTP:
    	if (@ftp_fget($this->conn_id, $tempHandle, $src, FTP_ASCII, 0)) {
        	rewind($tempHandle);
        	return stream_get_contents($tempHandle);
		} else {
			return false;
		}
	}

	function deleteFile($src) {
		$this->connectFTP();

		if (@ftp_rename($this->conn_id, $src, $src.'.done')) {
			// do nothing
		} else {
			die('Не могу переименовать с ФТП файл '.$src);
		}
	}

	function __destruct() {
		if ($this->_ftp_connected) ftp_close($this->conn_id);
	}
}
