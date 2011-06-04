<?php
$objectAttribute = eZContentObjectAttribute::fetch( 
	$Params['objectAttributeID'], 
	$Params['objectAttributeVersion']
);
$classAttribute  = $objectAttribute->contentClassAttribute();

$captchaParams = array(
	'length'     => $classAttribute->attribute( 'data_int1' ),
	'type'       => $classAttribute->attribute( 'data_int2' ),
	'width'      => $classAttribute->attribute( 'data_int3' ),
	'height'     => $classAttribute->attribute( 'data_int4' ),
	'noiseLevel' => $classAttribute->attribute( 'data_float1' ),
	'textColor'  => $classAttribute->attribute( 'data_text1' ),
	'bgColor'    => $classAttribute->attribute( 'data_text2' ),
	'noiseColor' => $classAttribute->attribute( 'data_text3' ),
);

$captcha = new IBCaptcha( $captchaParams );
$captcha->run(
	$objectAttribute->ID,
	( $Params['regenerate'] == 1 )
);
eZExecution::cleanExit();
?>