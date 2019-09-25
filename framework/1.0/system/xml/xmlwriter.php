<?php

define("XML_WRITER_ENCODING_DEFAULT", "utf-8");

class XmlWriter {

	var $Encoding;
	var $Stream;
	var $QuoteChar = "\"";

	var $attributeStatus = 0;
	var $elementStatus = array();
	var $inElement = 0;
	var $isRoot = 0;
	var $inWriteAttributeString = 0;

	function XmlWriter($encoding = XML_WRITER_ENCODING_DEFAULT) {
		$this->Encoding = $encoding;
	}

	function getXml() {
		return $this->Stream;
	}

	function Close() {
	}

	function WriteAttributeString($name, $value) {
		if (($this->attributeStatus == 0) && ($this->inElement == 1)) {
			$this->inWriteAttributeString = 1;
			if ($this->inElement == 1) {
				$this->Stream .= ' ';
			}
			$this->inElement = 1;
			$this->attributeStatus == 1;
			$value = $this->_recode($value);
			$this->attributeStatus == 0;
			$this->Stream .= $name . '=' . $this->QuoteChar . $value . $this->QuoteChar;
			$this->inWriteAttributeString = 0;
		}
		else {
			//May be Error
		}
	}

	/* Writes out a <![CDATA[...]]> block containing the specified text. */
	function WriteCData($text) {
		if ($this->inElement == 1) {
			$this->Stream .= '>';
			$this->inElement = 0;
		}
		if (($this->attributeStatus == 0) && (!strstr($text,']]>'))) {
			$this->Stream .= '<![CDATA[' . $text . ']]>';
		}
		else {
			//May be error
		}
	}

	/* Writes out a comment <!--...--> containing the specified text. */
	function WriteComment($text) {
		if ($this->attributeStatus == 0) {
			$this->Stream .= '<!--' . $text . '-->';
		}
		else {
			//May be error
		}
	}

	/* Writes the DOCTYPE declaration with the specified name and optional attributes. */
	function WriteDocType($name, $pubid = null, $sysid = null, $subset = null) {
	}

	/* Writes an element containing a string value. */
	function WriteElementString($name, $value) {
		if ($this->inElement == 1) {
			$this->Stream .= '>';
			$this->inElement = 0;
		}
		if (strlen(trim($value)) == 0) {
			$this->Stream .= '<' . $name . ' />';
		}
		elseif ($this->attributeStatus == 0) {
			$this->Stream .= '<' . $name . '>' . $this->_recode($value) . '</' . $name . '>';
		}
	}

	/* Closes the previous WriteStartAttribute call. */
	function WriteEndAttribute() {
		if ($this->attributeStatus == 1) {
			$this->Stream .= $this->QuoteChar;
			$this->attributeStatus = '0';
		}
	}

	/* Closes any open elements or attributes and puts the writer back in the Start state. */
	function WriteEndDocument() {
		$this->WriteEndAttribute();
		while (sizeof($this->elementStatus) > 0) {
			$this->WriteEndElement();
		}
	}

	/* Closes one element. */
	function WriteEndElement() {
		if (($this->inElement == 0) && (sizeof($this->elementStatus) > 0)) {
			$this->Stream .= '</' . array_pop($this->elementStatus) . '>';
		}
		elseif (($this->isRoot == 0) && (sizeof($this->elementStatus) > 0)) {
			$this->Stream .= ' />';
			$this->isRoot = 1;
			$this->inElement = 0;
			array_pop($this->elementStatus);
		}
		elseif ($this->attributeStatus == 1) {
			$this->WriteEndAttribute();
			$this->Stream .= ' />';
		}
		elseif (sizeof($this->elementStatus) > 0){
			$this->Stream .= '</' . array_pop($this->elementStatus) . '>';
		}
	}

	/* Writes out an entity reference as follows: & name;. */
	function WriteEntityRef($name) {

	}

	function _recode($value) {
		$value = str_replace('&',"&amp;",$value);
		if (($this->QuoteChar == '"') && ($this->inWriteAttributeString == 1)) {
			$value = str_replace('"',"&quot;",$value);
		}
		elseif (($this->QuoteChar == '\'')  && ($this->inWriteAttributeString == 1)) {
			$value = str_replace('\'',"&apos;",$value);
		}
		$value = str_replace('<',"&lt;",$value);
		$value = str_replace('>',"&gt;",$value);
		return $value;
	}

	/* Writes out a processing instruction with a space between the name and text as follows: <?name text ? >. */
	function WriteProcessingInstruction($name, $text) {
		if ($this->attributeStatus == 1) {
			$this->WriteEndAttribute();
		}
		if ((!strstr($name,'?>')) && (!strstr($name,'<?')) && (!strstr($text,'<?')) && (!strstr($text,'?>')) && ($name)) {
			$this->Stream .= '<?' . $name . ' ' . $text . '?>';
		}
	}

	/* Writes the start of an attribute. */
	function WriteStartAttribute($name) {
		if ($this->attributeStatus == 0) {
			$this->attributeStatus = 1;

			$this->Stream .= ' ' . $name . '=' . $this->QuoteChar;
		}
	}

	/* Writes raw markup manually from a string. */
	function WriteRaw($data) {
		$this->Stream .= $data;
	}

	/* Writes the XML declaration with the version "1.0" and the standalone attribute. */
	function WriteStartDocument($standalone = null) {
		if ((sizeof($this->elementStatus) == 0) && ($this->attributeStatus == 0)) {
			$this->Stream = '<?xml version=' . $this->QuoteChar . $this->version . $this->QuoteChar . ' encoding=' . $this->QuoteChar . $this->Encoding . $this->QuoteChar;
			if (($standalone) && (func_num_args() > 0)) {
				$this->Stream .= " standalone=". $this->QuoteChar . "yes" . $this->QuoteChar;
			}
			elseif ((!$standalone) && (func_num_args() > 0)) {
				$this->Stream .= " standalone=" . $this->QuoteChar . "no" . $this->QuoteChar;
			}
			$this->Stream .= '?>';
		}
		else {
			//Error.
		}
	}

	/* Writes out a start tag with the specified local name. */
	function WriteStartElement($name) {
		if ($this->inElement == 1) {
			$this->Stream .= '>';
		}
		array_push($this->elementStatus,$name);
		$this->Stream .= '<' . $name;
		$this->inElement = 1;
		$this->isRoot = 0;
	}

	/* Writes the given text content. */
	function WriteString($text) {
		if ($this->inElement == 1) {
			$this->Stream .= '>';
			$this->Stream .= $text;
			$this->inElement = 0;
		}
		if (($this->inElement == 1) || ($this->attributeStatus == 1)) {
			$this->Stream .= $text;
			$this->inElement = 0;
		}
	}

}
?>