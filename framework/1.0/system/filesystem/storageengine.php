<?php

class StorageEngine {

	var $base_path = '/tmp/';
	var $default_dir_mask = 0777;
	var $default_file_mask = 0777;

	function StorageEngine($init_path = null) {
		if (!empty($init_path) && is_dir($init_path) && is_writeable($init_path)) {
			$this->base_path = $init_path;
		}
	}

	function prepare_dir($key) {
		// check if exist
		if (!is_dir($this->base_path . $key)) {
			mkdir($this->base_path . $key, $this->default_dir_mask);
		}
		// check if writeable
		if (!is_writeable($this->base_path . $key)) {
			chmod($this->base_path . $key, $this->default_dir_mask);
		}
	}

	function prepare_filename($name, $key) {
		$info = pathinfo($name);
		$filename = $this->base_path . $key . '/' . $info["filename"] . '.' . $info['extension'];
		$i = 1;
		while (file_exists($filename)) {
			$i++;
			$filename = $this->base_path . $key . '/' . $info["filename"] . '_' . $i . '.' . $info['extension'];
		}
		return $filename;
	}

	function Store($file, $key) {
		$this->prepare_dir($key);
		$filename = $this->prepare_filename($file['name'], $key);
		if (move_uploaded_file($file["tmp_name"], $filename)) {
			return array(
							'varFilename' => str_replace($this->base_path, '', $filename),
							'varName' => str_replace($this->base_path . $key . '/', '', $filename)
							);
		}
		return false;
	}

}
