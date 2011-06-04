<?php
class IBCaptchaAttributes {
	private function __construct() {
	}
	
	static public function getDefinition() {
		return array(
			'length' => array(				
				'classAttribute' => 'data_int1',
				'defaultValue'   => '4',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterLength' )
	            )
			),
			'type' => array(
				'classAttribute' => 'data_int2',
				'defaultValue'   => '1',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterType' )
	            )				
			),
			'width' => array(				
				'classAttribute' => 'data_int3',
				'defaultValue'   => '200',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterWidth' )
	            )				
			),
			'height' => array(				
				'classAttribute' => 'data_int4',
				'defaultValue'   => '50',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterHeight' )
	            )				
			),
			'noiseLevel' => array(				
				'classAttribute' => 'data_float1',
				'defaultValue'   => '4.4',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterNoiseLevel' )
	            )				
			),
			'charsColor' => array(				
				'classAttribute' => 'data_text1',
				'defaultValue'   => 'aaaaaa',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterColor' )
	            )				
			),
			'bgColor' => array(				
				'classAttribute' => 'data_text2',
				'defaultValue'   => 'bbbbbb',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterColor' )
	            )				
			),						
			'noiseColor' => array(				
				'classAttribute' => 'data_text3',
				'defaultValue'   => 'cccccc',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterColor' )
	            )
			),
			'notUsedUserIDs' => array(				
				'classAttribute' => 'data_text4',
				'defaultValue'   => '14',
				'formDefinition' => new ezcInputFormDefinitionElement(
	            	ezcInputFormDefinitionElement::REQUIRED,
	                'callback',
	                array( 'IBCaptchaAttributeFilters', 'filterNotUsedUserIDs' )
	            )
			)			
		);
	}
	
	static public function getCaptchaAvailableTypes() {
		return array(
			1 => 'Alphabetic',
			2 => 'Numeric',
			3 => 'Alphanumeric'
		);
	}
	
	static public function getFormDefinition( $id ) {
		$attributesDefinition = self::getDefinition();
		$formDefinition = array();
		foreach( $attributesDefinition as $attribute => $attributeInfo ) {
			$attributeFormName = self::getBaseName() . self::getFormSeparator() . $attribute . self::getFormSeparator() . $id;
			$formDefinition[ $attributeFormName ] = $attributeInfo['formDefinition'];	
		}
		return $formDefinition;
	}
	
	static public function getBaseName() {
		return 'ContentClass_ibCaptcha';
	}
	
	static public function getFormSeparator() {
		return '_';
	}
	
	static public function getNotUsedUserIDsSeparator() {
		return ',';
	}
}
?>