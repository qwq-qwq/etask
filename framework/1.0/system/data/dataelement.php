<?php
/**
	 * Data Element, which provide a node-like functionality
	 *
	 * @package system
	 */

Kernel::Import('system.data.Element');

class DataElement extends Element {

	/**
		 * List of Elements childs
		 *
		 * @var DataElements array
		 */
	var $childs;
	/**
		 * Assoc List of DataElement childs by name
		 *
		 * @var DataElements array
		 */
	var $childsByName;

	/**
		 * Construct DataElement
		 *
		 * @param string $name
		 * @return DataElement
		 */
	function DataElement($name) {
		Element::Element( $name );
		$this->childs = array();
		$this->childsByName = array();
	}

	/**
		 * Add child to node
		 *
		 * @param DataElement $dataElement
		 */
	function addChild( &$dataElement ) {
		$this->childs[] = &$dataElement;
		$this->childsByName[ $dataElement->getName() ] = &$dataElement;
	}

	function removeChild( $name ) {
		unset( $this->childsByName[ $name ] );
	}

	/**
		 * Get child by index
		 *
		 * @param int $index
		 * @return DataElement
		 */
	function &getChildByIndex( $index ) {
		return $this->childs[ $index ];
	}

	/**
		 * Get child by name
		 *
		 * @param string $name
		 * @return DataElement
		 */
	function &getChildByName( $name ) {
		return $this->childsByName[ $name ];
	}

	/**
		 * Clear note, remove all childs
		 *
		 */
	function clear() {
		parent::clear();
		$this->childs = array();
		$this->childsByName = array();
	}

	/**
		 * Return count of childs
		 *
		 * @return int
		 */
	function size() {
		return count($this->childs);
	}

	/**
		 * Convert value to XML
		 *
		 * @return string xml
		 */
	function valueToXML() {
		$xml = parent::valueToXML();
		foreach ($this->childs as $child )
		{
			$xml .= $child->toXML();
		}
		return $xml;
	}
}

?>