<?php

class Kernel {

	public static function Import($className) {
		$className = strtolower($className);
		if ($pos = strpos($className, '.*')) {
			$dirName = substr($className, 0, $pos);
			Kernel::ImportDir($dirName);
		} else {
			$fullPath = Kernel::createPath($className);
			Kernel::ImportFilename($fullPath.'.php');
		}
	}

	public static function RenderBackTrace($backtrace) {
		?>
		 	<div style="padding: 5px 5px 5px 5px; background: #FFAAAA">
		 	<table border="0" cellpadding="0" cellspacing="0">
		<?
	     $files = $backtrace;
	     foreach ($files as $row => $file) {
	     	echo '<tr>';
	     	echo '<td>' . $file['file'] . ' : ' . $file['line'] . '</td>';
	     	echo '<td width="50">&nbsp;</td>';
	     	if ($file['function']) {
	     		echo '<td>' . ( (!empty($file['class'])) ? $file['class'] . '::' : '' ) . $file['function'] . '</td>';
	     	} else {
	     		echo '<td></td>';
	     	}
	     	echo '</tr>';
	     }
		?>
			</table>
			</div>
		<?
	}

	public static function RaiseError($message='', $interrupt = false) {
		if (ENABLE_INTERNAL_DEBUG) {
		?>
		<div>
		<div align="left" style="padding: 5px 5px 5px 5px; background: #FF8888">
			<b><?php echo $message;?></b>
		</div>
		<?Kernel::RenderBackTrace(debug_backtrace());?>
		</div>
		<?
		if ( $interrupt == true ) die();
		}
	}

	public static function createPath($className) {
		$pathPrefix = str_replace('.', '/', $className);
		if ( strpos($className, 'system.') === 0 ) {
			$fullPath = FRAMEWORK_PATH.FRAMEWORK_VERSION.'/'.$pathPrefix;
		} else {
			$fullPath = PROJECT_PATH.$pathPrefix;
		}
		return $fullPath;
	}

	public static function ImportFilename($fullPath) {
		static $imported = array();
		if ( isset($imported[$fullPath]) )  {
			return;
		}
		$result = include_once($fullPath);
		if(!$result) {
			Kernel::RaiseError("Unable to import from: <b>$fullPath</b>");
		}
		$imported[$fullPath] = true;
	}

	public static function ImportDir($dirName) {
		$path = Kernel::createPath(strtolower($dirName));
		$files = glob($path . '/*');

		foreach ($files as $file)	{
			$dirList[$file] = filetype($file);
		}
		foreach ($dirList as $file => $type) {
			if ($type=='dir') {
				Kernel::Import($dirName.'.'.basename($file).'.*');
			} else {
				Kernel::ImportFilename($file);
			}
		}
	}

	public static function ProcessPage($pageObject) {
		$pageObject->processComponent();
		$response = $pageObject->getResponse();
		$response->display();
	}

}

function framework_kernel_error_handler($errno, $errstr, $errfile, $errline) {
	$errorTypeName = 'E_CUSTOM';
	switch ($errno) {
		case E_USER_ERROR:
			$errorTypeName = 'E_USER_ERROR';
			break;
		case E_COMPILE_ERROR:
			$errorTypeName = 'E_COMPILE_ERROR';
			break;
		case E_COMPILE_WARNING:
			$errorTypeName = 'E_COMPILE_WARNING';
			break;
		case E_CORE_ERROR:
			$errorTypeName = 'E_CORE_ERROR';
			break;
		case E_CORE_WARNING:
			$errorTypeName = 'E_CORE_WARNING';
			break;
		case E_ERROR:
			$errorTypeName = 'E_ERROR';
			break;
		case E_NOTICE:
			$errorTypeName = 'E_NOTICE';
			return;
			break;
		case E_PARSE:
			$errorTypeName = 'E_PARSE';
			break;
		case E_WARNING:
			$errorTypeName = 'E_WARNING';
			break;
		default:
			break;
	}
	$errorStr = '<br/>ERROR '.$errorTypeName.' in file '.$errfile.' line '.$errline.' <br/>';
	Kernel::RaiseError($errorStr.$errstr);
}

set_error_handler('framework_kernel_error_handler');
