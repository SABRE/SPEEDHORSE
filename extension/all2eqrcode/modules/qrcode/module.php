<?php

$Module = array( 'name' => 'qrcode' );

$ViewList = array();
$ViewList['encode'] = array('script' => 'encode.php',
                                 'functions' => array( 'encode' ),
                                 'params' => array( 'type', 'text', 'width', 'height' )
                                );

$FunctionList = array();
$FunctionList['encode'] = array();

?>
