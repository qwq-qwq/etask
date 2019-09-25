<?php

/**
	 * Abstract Element, which provide easy convert
	 * child class to part of document
	 *
	 * @package system
	 */

class Element {
	/**
		 * name of element
		 *
		 * @var string
		 */
	var $name;

	/**
		 * Array of attributes
		 *
		 * @var assoc array
		 */
	var $attributes;

	/**
		 * Construct element
		 *
		 * @param string $name
		 * @return XMLElement
		 */
	function Element($name) {
		$this->name = $name;
		$this->attributes = array();
	}

	/**
		 * Get name of element
		 *
		 * @return string
		 */
	function getName() {
		return $this->name;
	}

	/**
		 * Set name of element
		 *
		 * @param string $name
		 */
	function setName( $name ) {
		$this->name = $name;
	}

	/**
		 * Set attribute of element
		 *
		 * @param string $name
		 * @param string $value
		 */
	function setAttribute( $name, $value) {
		$this->attributes[ $name ] = $value;
	}

	/**
		 * Get attribute value by name
		 *
		 * @param string $name
		 * @return string
		 */
	function getAttribute( $name ) {
		return $this->attributes[ $name ];
	}

	/**
		 * return count of attributes in element
		 *
		 * @return int
		 */
	function getAttributesCount() {
		return count($this->attributes);
	}

	/**
		 * Convert element to XML
		 *
		 * @return string xml
		 */
	function toXML() {
		$value = $this->valueToXML();

		$xml = '';
		if( is_null( $value ) || strlen($value) == 0 )
		{
			$xml .= '<'.$this->name.$this->__renderAttrs().'/>';
		}
		else
		{
			$xml .= '<'.$this->name.$this->__renderAttrs().'>';
			$xml .= $value;
			$xml .= '</'.$this->name.'>';
		}
		return $xml;
	}

	/**
		 * Convert element value to XML
		 *
		 * @return string xml
		 */
	function valueToXML() {
		return '';
	}

	/**
		 * Clear element data
		 *
		 */
	function clear() {
		$this->attributes = array();
	}

	/**
		 * render attributes of element to xml
		 *
		 * @return string
		 */
	function __renderAttrs() {
		$xml = '';
		if( count($this->attributes) )
		{
			foreach ($this->attributes as $key => $value)
			{
				$xml .= ' ';
				$xml .= $key.'=';
				$xml .= '"'.$value.'"';
			}
		}
		return $xml;
	}
}

?>