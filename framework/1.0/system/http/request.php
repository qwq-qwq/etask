<?php

Kernel::Import('system.validation.validate');

class request {

	protected $requestData;
	protected $files;
	protected $validateErrors = array();

	function __construct(){
		$this->requestData = $_REQUEST;
		$this->files = $_FILES;
	}
	
	public function getFiles($name) {
		return $this->files[$name];
	}
	
	public function getRequest() {
		return $this->requestData;
	}
	
	public function getErrors() {
		return $this->validateErrors;
	}
	
	public function Value($name, $defaultValue = null){
		if( $this->isExist($name) ){
			if (get_magic_quotes_gpc()) {
				if (is_array($this->requestData[$name])) {
					return $this->requestData[$name];
				} else return stripslashes($this->requestData[$name]);
			}
			else return $this->requestData[$name];
		} else {
			return $defaultValue;
		}
	}
	
	public function isExist($name){
		return isset($this->requestData[$name]);
	}
	
	public function getString($name, $validator = null, $defaultValue = null, $stripWhite = true) {
		$value = $this->Value($name, $defaultValue);
		// Stripping leading and trailing whitespaces
		if ($stripWhite) $value = trim($value);
		// run validator
		if (!is_null($validator) && method_exists('Validate', 'is'.$validator)) {
			if (!call_user_func(array('Validate', 'is'.$validator), $value)) $this->validateErrors[$name] = $validator;
		}
		return $value;
	}
	
	public function setError($field, $validator = 'customError') {
		$this->validateErrors[$field] = $validator;
	}
	
	public function getNumber($name, $validator = null, $defaultValue = null) {
		$value = (int) $this->Value($name, $defaultValue);
		// run validator
		if (!is_null($validator) && method_exists('Validate', 'is'.$validator)) {
			if (!call_user_func(array('Validate', 'is'.$validator), $value)) $this->validateErrors[$name] = $validator;
		}
		return $value;
	}
	
	public function getDate($name, $validator = null) {
		$day = $this->getNumber($name.'Day');
		$month = $this->getNumber($name.'Month');
		$year = $this->getNumber($name.'Year');
		$hour = $this->getNumber($name.'Hour');
		$minute = $this->getNumber($name.'Minute');
		$value = mktime($hour, $minute, 1, $month, $day, $year);
		
		if (!is_null($validator) && method_exists('Validate', $validator)) {
			if (!call_user_func(array('Validate', $validator), $value)) $this->validateErrors[$name] = $validator;
		}
		return $value;
	}
	
	public function getTime($name, $validator = null) {
		$hour = $this->getNumber($name.'Hour');
		$minute = $this->getNumber($name.'Minute');
		$value = sprintf('%02d:%02d', $hour, $minute);
		
		if (!is_null($validator) && method_exists('Validate', $validator)) {
			if (!call_user_func(array('Validate', $validator), $value)) $this->validateErrors[$name] = $validator;
		}
		return $value;
	}

}
