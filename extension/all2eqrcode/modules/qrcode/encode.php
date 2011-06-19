<?php

include_once('extension/all2eqrcode/classes/all2eQRCodeClass.php');

$type = $Params['type']?$Params['type']:"text";
$text = $Params['text']?$Params['text']:"QR-Code";
$width = $Params['width']?$Params['width']:250;
$height = $Params['height']?$Params['height']:250;

$src = all2eQRCodeClass::encode( $type, $text, $width, $height );

echo "<img src=\"$src\" alt=\"$text\" width=\"$width\" height=\"$height\"  />";

eZExecution::cleanExit();

?>

