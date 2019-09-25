<?php
/*
*	MySQL 4.1 charsers (update it using SHOW CHARACTER SET sql)
*
*/
/**
* DEC West European
*
*/
define('DB_CHARSET_DEC8', 'dec8');
/**
* DOS West European
*
*/
define('DB_CHARSET_CP850', 'cp850');
/**
* HP West European
*
*/
define('DB_CHARSET_HP8', 'hp8');
/**
* KOI8-R Relcom Russian
*
*/
define('DB_CHARSET_KOI8R', 'koi8r');
/**
* ISO 8859-1 West European
*
*/
define('DB_CHARSET_LATIN1', 'latin1');
/**
* ISO 8859-2 Central European
*
*/
define('DB_CHARSET_LATIN2', 'latin2');
/**
* 7bit Swedish
*
*/
define('DB_CHARSET_SWE7', 'swe7');
/**
* US ASCII
*
*/
define('DB_CHARSET_ASCII', 'ascii');
/**
* ISO 8859-8 Hebrew
*
*/
define('DB_CHARSET_HEBREW', 'hebrew');
/**
* KOI8-U Ukrainian
*
*/
define('DB_CHARSET_KOI8U', 'koi8u');
/**
* ISO 8859-7 Greek
*
*/
define('DB_CHARSET_GREEK', 'greek');
/**
* Windows Central European
*
*/
define('DB_CHARSET_CP1250', 'cp1250');
/**
* ISO 8859-9 Turkish
*
*/
define('DB_CHARSET_LATIN5', 'latin5');
/**
* ARMSCII-8 Armenian
*
*/
define('DB_CHARSET_ARMSCII8', 'armscii8');
/**
* UTF-8 Unicode
*
*/
define('DB_CHARSET_UTF8', 'utf8');
/**
* DOS Russian
*
*/
define('DB_CHARSET_CP866', 'cp866');
/**
* DOS Kamenicky Czech-Slovak
*
*/
define('DB_CHARSET_KEYBCS2', 'keybcs2');
/**
* Mac Central European
*
*/
define('DB_CHARSET_MACCE', 'macce');
/**
* Mac West European
*
*/
define('DB_CHARSET_MACROMAN', 'macroman');
/**
* DOS Central European
*
*/
define('DB_CHARSET_CP852', 'cp852');
/**
* ISO 8859-13 Baltic
*
*/
define('DB_CHARSET_LATIN7', 'latin7');
/**
* Windows Cyrillic
*
*/
define('DB_CHARSET_CP1251', 'cp1251');
/**
* Windows Arabic
*
*/
define('DB_CHARSET_CP1256', 'cp1256');
/**
* Windows Baltic
*
*/
define('DB_CHARSET_CP1257', 'cp1257');
/**
* Binary pseudo charset
*
*/
define('DB_CHARSET_BINARY', 'binary');
/**
* GEOSTD8 Georgian
*
*/
define('DB_CHARSET_GEOSTD8', 'geostd8');

class MySQLConnectionProperties {

	var $proto;
	var $host;
	var $user;
	var $password;
	var $database;
	var $encoding;

	function MySQLConnectionProperties($host, $user, $password, $database) {
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->proto = 'mysql';
		$this->encoding = DB_CHARSET_UTF8;
	}

	function setEncoding($enc) {
		$this->encoding = $enc;
	}

	function getHost() {
		return $this->host;
	}

	function getUser() {
		return $this->user;
	}

	function getPassword() {
		return $this->password;
	}

	function getDatabase() {
		return $this->database;
	}

	function setDatabase($database) {
		$this->database = $database;
	}

	function toURIString() {
		return 'mysql://'.$this->getUser().':'.$this->getPassword().'@'.$this->getHost().'/'.$this->getDatabase();
	}

	public static function createByURI( $stringURI ) {
		$params = parse_url($stringURI);
		$proto = $params["scheme"];
		$user = $params["user"];
		$password = $params["pass"];
		$host = $params["host"];
		$database = substr($params["path"], 1, strlen($params["path"]));
		return new MySQLConnectionProperties($host, $user, $password, $database);
	}
}

?>