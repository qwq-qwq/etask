<?php
if (!defined('SESSION_SAVE_HANDLER')) {
	/**
	 * php | files
	 *
	 */
	define('SESSION_SAVE_HANDLER', 'files');
}
if (!defined('SESSION_SAVE_PATH')) {
	define('SESSION_SAVE_PATH', PROJECT_CACHE.'session/');
}

class Session {

	var $SessionID = "framework_session";
	var $SessionStorage;

	function Session($SessionID = null) {
		if (!is_null($SessionID)) $this->SessionID = $SessionID;
		if (SESSION_SAVE_HANDLER === 'files') {
			$this->SessionStorage = new FilesSession($this->SessionID);
		} else {
			$this->SessionStorage = new PhpSession($this->SessionID);
		}
	}

	function Get($name, $default = null) {
		return $this->SessionStorage->Get($name, $default);
	}

	function Set($name, $value) {
		$this->SessionStorage->Set($name, $value);
	}

	function Remove($name) {
		$this->SessionStorage->Remove($name);
	}

	function Close() {
		$this->SessionStorage->Close();
	}

	function Clear() {
		$this->SessionStorage->Clear();
	}
}

class PhpSession {

	function PhpSession($SessionID = null) {
		session_name($SessionID);
		session_start();
	}

	function Get($name, $default = null) {
		return ( isset($_SESSION[$name]) ) ? $_SESSION[$name] : $default;
	}

	function Set($name, $value) {
		$_SESSION[$name] = $value;
	}

	function Remove($name) {
		unset($_SESSION[$name]);
	}

	function Close() {
		session_commit();
	}

	function Clear() {
		$_SESSION = array();
	}
}

class FilesSession {

	function FilesSession( $SessionID ) {
		$this->SessionID = $SessionID;
		$_SESSION = $this->load();
	}

	function createPath() {
		if (!empty($_COOKIE[$this->SessionID])) {
			$sessionName = $_COOKIE[$this->SessionID];
		} else {
			$sessionName = md5($this->SessionID.getenv('REMOTE_ADDR').time());
			setcookie($this->SessionID, $sessionName);
		}
		$sessionFileName = $sessionName.'.'.getenv('REMOTE_ADDR').'.session';
		$sessionFolderName = substr($sessionFileName, 0, 3);
		if(!is_dir(SESSION_SAVE_PATH.$sessionFolderName)) {
			mkdir(SESSION_SAVE_PATH.$sessionFolderName, 0775);
		}
		return SESSION_SAVE_PATH.$sessionFolderName.'/'.$sessionFileName;
	}

	function load() {
		$path = $this->createPath();
		$data = '';
		if ( file_exists($path) ) {
			$f = @fopen( $path, 'r' );
			if( !$f ) {
				return array();
			}
			$data = @fread($f, filesize( $path ) );
			if( !$data ) {
				return array();
			}
			@fclose($f);
		}
		return unserialize($data);
	}

	function save() {
		$path = $this->createPath();
		$f = @fopen( $path, 'w' );
		@fwrite($f, serialize( $_SESSION ) );
		@fclose($f);
	}

	function Get($name, $default = null) {
		return ( isset($_SESSION[$name]) ) ? $_SESSION[$name] : $default;
	}

	function Set($name, $value) {
		$_SESSION[$name] = $value;
	}

	function Remove($name) {
		unset($_SESSION[$name]);
	}

	function Close() {
		$this->save();
	}

	function Clear() {
		$_SESSION = array();
	}

}
