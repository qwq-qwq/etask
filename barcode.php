<?php
$code = $_GET['code'];
$alg = $_GET['type'] == 39;
// Define variable to prevent hacking
define('IN_CB',true);
// Including all required classes
$base_path = dirname(realpath(__FILE__));
require($base_path.'/classes/unit/barcode/index.php');
require($base_path.'/classes/unit/barcode/FColor.php');
require($base_path.'/classes/unit/barcode/BarCode.php');
require($base_path.'/classes/unit/barcode/FDrawing.php');
// including the barcode technology
if ($alg) {
	include($base_path.'/classes/unit/barcode/code39.barcode.php');
} else {
	include($base_path.'/classes/unit/barcode/ean13.barcode.php');
}
// Creating some Color (arguments are R, G, B)
$color_black = new FColor(0,0,0);
$color_white = new FColor(255,255,255);

if ($alg) {
	$code_generated = new code39(60,$color_black,$color_white,2,$code,4);
} else {
	//var_dump(substr((string)$code, 0, 12));
	$code_generated = new ean13(60,$color_black,$color_white,2,substr($code,0,12),4);
}
/* Here is the list of the arguments
1 - Width
2 - Height
3 - Filename (empty : display on screen)
4 - Background color */
$drawing = new FDrawing(1024,1024,'',$color_white);
$drawing->init(); // You must call this method to initialize the image
$drawing->add_barcode($code_generated);
$drawing->draw_all();
$im = $drawing->get_im();

// Next line create the little picture, the barcode is being copied inside
$im2 = imagecreate($code_generated->lastX,$code_generated->lastY);
imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
$drawing->set_im($im2);

header('Content-Type: image/png');
$drawing->finish(IMG_FORMAT_PNG);
