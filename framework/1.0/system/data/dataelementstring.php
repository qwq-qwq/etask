<?php
/**
	 * String Element, which provide a name=>value structure in document
	 *
	 * @package system
	 */

Kernel::Import('system.data.DataElement');

class DataElementString extends DataElement {
	/**
		 * String value
		 *
		 * @var string
		 */
	var $value;

	/**
		 * Contruct class
		 *
		 * @param string $name
		 * @param string $value
		 * @return DataElementString
		 */
	function DataElementString($name, $value) {
		DataElement::DataElement($name);
		$this->value = $value;
	}

	/**
		 * Set value of element
		 *
		 * @param string $value
		 */
	function setValue( $value ) {
		$this->value = $value;
	}

	/**
		 * Get value of element
		 *
		 * @return string
		 */
	function getValue() {
		return $this->value;
	}

	/**
		 * Convert node value to xml
		 *
		 * @return string xml
		 */
	function valueToXML() {
		return parent::valueToXML().$this->value;
	}
}

?>