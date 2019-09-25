<?php

class Validate {

	public static function isExternalIP ($ipNumber) {
		if(!ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $ipNumber)) {
			return false;
		}
		list ($ip1, $ip2, $ip3, $ip4) = split("\.", $ipNumber);
		if( ($ip1 == 127) || ($ip1 == 10) || (($ip1 == 192) && ($ip2 == 168)) || (($ip1 == 172) && ($ip2 == 16)) || ($ip1 > 255) || ($ip2 > 255) || ($ip3 > 255) || ($ip4 > 255)) {
			return false;
		}
		return true;
	}

	public static function isDomainName($string) {
		if( preg_match("/^[0-9a-z]([-]?[0-9a-z])*\\.[a-wyz][a-z](g|l|m|pa|t|u|v|fo)?$/", $string) ) {
			return true;
		}
		return false;
	}

	public static function isEmail($address) {
		if (!preg_match("/^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](g|l|m|pa|t|u|v|fo)?$/", $address)) {
			return false;
		}
		return true;
	}

	public static function isNotEmpty($fieldValue) {
		return !empty($fieldValue);
	}
	
	public static function isStr4($fieldValue) {
		return (strlen($fieldValue) >= 4);
	}
	
	public static function isGLN($fieldValue) {
		mb_internal_encoding("UTF-8");
		$len = mb_strlen($fieldValue);
		if ($len != 13) return false;
		$cur = 0;
		$gln = array();

		while($cur < $len){
			$char = intval($fieldValue{$cur});
			array_push($gln, $char);
			$cur = $cur + 1;
		}

		$controlchar = intval($gln[$len-1]);
		$even = 0;
		$odd = 0;
		$checksum = 0;
		$gln = array_reverse($gln);
		for ($i=1; $i<$len;$i++) {
			if ($i % 2) {
				$even += $gln[$i];
			} else {
				$odd += $gln[$i];
			}
		}
		$checksum = $even*3 + $odd;
		$checkchar = 10 - ($checksum % 10);
		if ($checkchar > 9) $checkchar = 0;
		if ($controlchar === $checkchar) return true;
		return false;
	}
}

?>