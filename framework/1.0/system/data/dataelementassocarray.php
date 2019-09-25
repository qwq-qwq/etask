<?php

/**
	 * Assoc Array Element, which provide a array of name=>value structures in document
	 *
	 * @package system
	 */

Kernel::Import('system.data.DataElementString');

class DataElementAssocArray extends DataElement {

	var $value;
	/**
		 * Construct class
		 *
		 * @param string $name
		 * @param string $value
		 * @return DataElementAssocArray
		 */
	function DataElementAssocArray($name, $value=array() ) {
		parent::DataElement($name);
		$this->value = $value;
	}

	/**
		 * Clear values
		 *
		 */
	function clear() {
		parent::clear();
		$this->value = array();
	}

	/**
		 * Set field of assoc array value
		 *
		 * @param string $name
		 * @param string $value
		 */
	function setField( $name, $value ) {
		$this->value[$name] = $value;
	}

	/**
		 * Delete field from assoc array
		 *
		 * @param string $name
		 */
	function deleteField($name) {
		unset( $this->value[$name] );
	}

	function arrayToXml($array) {
		$xml = '';
		foreach ($array as $key=>$value) {
			if (is_array($value)) {
				if (is_numeric($key)) {
					$xml .= '<item key="'.$key.'">'.$this->arrayToXml($value).'</item>';
				} else {
					$xml .= '<'.$key.'>'.$this->arrayToXml($value).'</'.$key.'>';
				}
			} else {
				if (is_numeric($key)) {
					$xml .= '<item key="'.$key.'">'.$value.'</item>';
				} else {
					$xml .= '<'.$key.'>'.$value.'</'.$key.'>';
				}
			}
		}
		return $xml;
	}

	/**
		 * Convert value to XML
		 *
		 * @return string xml
		 */
	function valueToXML() {
		$xml = $this->arrayToXml($this->value);
		return $xml;
	}
}

?>