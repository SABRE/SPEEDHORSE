<?php
$Module = array(
	'name'            => 'IB Captcha',
    'variable_params' => true
);

$ViewList = array();
$ViewList['get'] = array(
    'script' => 'get.php',
    'params' => array( 
		'objectAttributeID',
		'objectAttributeVersion',
		'regenerate'
	)
);
?>
