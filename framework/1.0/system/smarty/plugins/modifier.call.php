<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     nl2br<br>
 * Date:     Feb 26, 2003
 * Purpose:  convert \r\n, \r or \n to <<br>>
 * Input:<br>
 *         - contents = contents to replace
 *         - preceed_test = if true, includes preceeding break tags
 *           in replacement
 * Example:  {$text|nl2br}
 * @link http://smarty.php.net/manual/en/language.modifier.nl2br.php
 *          nl2br (Smarty online manual)
 * @version  1.0
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_call($phone)
{
	// validate a phone number
	$phone = preg_replace('/[^0-9]/', '', $phone); # remove non-numbers
	if ( ! preg_match('/^1?[0-9]{10}$/', $phone)) {
		return;
	}
	$id = rand(1000,9999);	
	return '<a style="cursor:pointer;" onclick="callnumber(\''.$phone.'\', \'#img'.$id.'\')" title="Звонить" class="call"><img id="img'.$id.'" src="img/callphone.png" alt="Звонить" title="Звонить" width="16" height="16" border="0" /></a>';
}

/* vim: set expandtab: */

?>
