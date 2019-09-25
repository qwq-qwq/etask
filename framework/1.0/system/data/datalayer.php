<?php

/**
	 * Data layer - is a layer and factory for
	 * acceptable kind of data types
	 *
	 * @package system
	 */

Kernel::Import('system.data.DataElement');
Kernel::Import('system.data.DataElementString');
Kernel::Import('system.data.DataElementAssocArray');

class DataLayer extends DataElement {
	function DataLayer($name) {
		parent::DataElement($name);
	}


	/**
		 * Add name value to datalayer
		 *
		 * @param string $name
		 * @param string $value
		 */
	function addValue($name, $value) {
		if (!is_array($value)) {
			$this->addChild(new DataElementString($name, $value));
		} else {
			$this->addChild(new DataElementAssocArray($name, $value));
		}
	}

}

?>