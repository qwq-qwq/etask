<?php
/**
* File manipulation class
*
* @version 1.0
* @package system
*
*/

class File
{
	/**
	 * @var string
	 */
	var $__fileName;

	var $__fileResource;

	/**
	 * File constructor
	 *
	 * @return File
	 */
	function File( $fileName )
	{
		$this->__fileName = $fileName;
	}

	function open( $mode = 'r' )
	{
		if ( $mode == 'r' )
		{
			if ( is_file($this->__fileName) )
			{
				return $this->__fileResource = fopen($this->__fileName, $mode);
			}
		}
		else
		{
			return $this->__fileResource = fopen($this->__fileName, $mode);
		}
	}

	function read()
	{
		if ( empty($this->__fileResource) )
		{
			if ( !$this->open() )
			{
				return null;
			}
		}

		return fread($this->__fileResource, filesize($this->__fileName));
	}

	function write($data)
	{
		$this->open('w');

		fwrite($this->__fileResource, $data);

		$this->close();
	}

	function delete()
	{
		if ( is_file($this->__fileName) )
		{
			unlink($this->__fileName);
		}
	}

	function close()
	{
		fclose($this->__fileResource);
		$this->__fileResource = null;
	}

	function exists()
	{
		return is_file($this->__fileName);
	}

	function copyToFile( $destinationFile )
	{
		if ( $this->exists() )
		{
			copy($this->__fileName, $destinationFile);
		}
	}

	function copyToNew( $destinationDir )
	{
		if ( $this->exists() )
		{
			$newName = $this->generateUniqueName() . '.' . $this->getFileExtension();
			copy($this->__fileName, $destinationDir . $newName);

			return $newName;
		}

		return null;
	}

	function generateUniqueName()
	{
		$microTime = microtime();
		$randomNumber = rand(0, 999999);
		$uniqueName = md5($microTime . $randomNumber);
		return $uniqueName;
	}

	function getFileExtension()
	{
		preg_match('/\.\w+$/', $this->__fileName, $searchResults);
		return @$searchResults[0];
	}

	/**
	 * @return int
	 */
	function getCreationTime()
	{
		return filectime($this->__fileName);
	}
}
?>