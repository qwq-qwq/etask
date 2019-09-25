<?php
/**
* Directory filesystem class for working with directories
*
* @version 1.0
* @package system
*
*/

class Dir
{
	var $path;

	var $resource;

	var $filesList;

	function Dir($path)
	{
		$this->path = $path;
		$this->filesList = array();
	}

	function open()
	{
		if ( $this->exists() )
		{
			$this->resource = opendir($this->path);

			return true;
		}

		return false;
	}

	function getFilesList()
	{
		if ( count($this->filesList) == 0 )
		{
			if ( $this->isOpened() )
			{
				while ( $file = readdir($this->resource) )
				{
					$this->filesList[] = $file;
				}
			}

		}

		return $this->filesList;
	}

	function findFileByName( $seachrName )
	{
		$list = $this->getFilesList();

		foreach ( $list as $fileName )
		{
			if ( $seachrName == $fileName )
			{
				return true;
			}
		}

		return false;
	}

	function __findKeyWords( $keyArray, $hayStack )
	{
		foreach ( $keyArray as $key )
		{
			if ( strpos($hayStack, $key) === false )
			{
				return false;
			}
		}

		return true;
	}

	function findFileByKey( $key )
	{
		if ( strpos($key, ' ') )
		{
			$key = split(' ', $key);
		}
		else
		{
			$key = array($key);
		}

		$list = $this->getFilesList();

		$found = array();

		foreach ( $list as $fileName )
		{
			if ( $this->__findKeyWords($key, $fileName) !== false )
			{
				$found[] = $fileName;
			}
		}

		return $found;
	}

	function getPath()
	{
		return $this->path;
	}

	function exists()
	{
		return is_dir($this->path);
	}

	function isOpened()
	{
		return ( is_resource($this->resource) );
	}
}
?>