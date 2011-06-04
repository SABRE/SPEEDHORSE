<?php
class IBCaptchaType extends eZDataType {
    const DATATYPE_STRING = 'ibcaptcha';
    
    public $attributesDefinition = array();	
	public $availableTypes       = array();

    public function __construct() {
        parent::__construct( 
			self::DATATYPE_STRING, 
			'IB Captcha',
			array(
				'serialize_supported'  => true
			)
		);
		
		$this->attributesDefinition = IBCaptchaAttributes::getDefinition();
		$this->availableTypes       = IBCaptchaAttributes::getCaptchaAvailableTypes();
    }

    public function initializeClassAttribute( $classAttribute ) {
    	foreach( $this->attributesDefinition as $attributeName => $attributeInfo ) {
    		if( $classAttribute->attribute( $attributeInfo['classAttribute'] ) === null ) {
				$classAttribute->setAttribute( 
					$attributeInfo['classAttribute'],
                    $attributeInfo['defaultValue']
				);	
    		}    		
    	}
    }

    public function validateClassAttributeHTTPInput( $http, $base, $classAttribute ) {
    	if( !$http->hasPostVariable( 'StoreButton' ) && !$http->hasPostVariable( 'ApplyButton' ) ) {
    		return eZInputValidator::STATE_INVALID;	
    	}        	
    	
		$form = new ezcInputForm( 
			INPUT_POST, 
			IBCaptchaAttributes::getFormDefinition( $classAttribute->ID ) 
		);
		$invalidProps = $form->getInvalidProperties();
		
		
		if( count( $invalidProps ) > 0 ) {
			return eZInputValidator::STATE_INVALID;	
		} else {
			return eZInputValidator::STATE_ACCEPTED;	
		}       
    }

    public function fetchClassAttributeHTTPInput( $http, $base, $classAttribute ) {
    	if( !$http->hasPostVariable( 'StoreButton' ) && !$http->hasPostVariable( 'ApplyButton' ) ) {
    		return false;	
    	}
		    	
		$form = new ezcInputForm( 
			INPUT_POST, 
			IBCaptchaAttributes::getFormDefinition( $classAttribute->ID ) 
		);
		$validProps = $form->getValidProperties();
		
		foreach( $validProps as $attrbuteFormName ) {
			$attrbuteName = str_replace( 
				IBCaptchaAttributes::getBaseName() . IBCaptchaAttributes::getFormSeparator(), 
				'', 
				$attrbuteFormName
			);
			$tmp          = explode( IBCaptchaAttributes::getFormSeparator(), $attrbuteName );
			$attrbuteName = $tmp[0];
			
			$classAttribute->setAttribute(
            	$this->attributesDefinition[$attrbuteName]['classAttribute'],
            	$form->$attrbuteFormName
        	);
		}
    }
    
    public function initializeObjectAttribute( $attribute, $version, $originalAttribute ) {
		//Check users for whom captcha will not be used
		$currentUserID  = eZUser::currentUserID();
		$notUsedUserIDs = explode( 
			IBCaptchaAttributes::getNotUsedUserIDsSeparator() . ' ',
			$attribute->contentClassAttribute()->attribute( 'data_text4' )
		);
		if( in_array( $currentUserID, $notUsedUserIDs ) ) {
			$attribute->setAttribute( 'data_int', 1 );
			$attribute->setAttribute( 'data_text', 'skipped' );
			$attribute->store();
		} else {
			$attribute->setAttribute( 'data_int', $originalAttribute->attribute( 'data_int' ) );
        	$attribute->store();			
		}    			
    }

    public function objectAttributeContent( $objectAttribute ) {
    	return array(
    		'entered'      => $objectAttribute->attribute( 'data_int' ),
    		'captcha_text' => $objectAttribute->attribute( 'data_text' )
		);    	
    }

    public function validateObjectAttributeHTTPInput( $http, $base, $objectAttribute ) {    	
    	//Check if captcha allready entered
		if( $objectAttribute->attribute( 'data_int' ) == true ) {
			return eZInputValidator::STATE_ACCEPTED;	
		}	                                         
		
		return $this->validateAttributeHTTPInput( $http, $base, $objectAttribute );
    }
    
    private function validateAttributeHTTPInput( $http, $base, $objectAttribute ) {
		//Check users for whom captcha will not be used
		$currentUserID  = eZUser::currentUserID();
		$notUsedUserIDs = explode( 
			IBCaptchaAttributes::getNotUsedUserIDsSeparator() . ' ',
			$objectAttribute->contentClassAttribute()->attribute( 'data_text4' )
		);
		if( in_array( $currentUserID, $notUsedUserIDs ) ) {
			return eZInputValidator::STATE_ACCEPTED;
		}
		  
		//Check captcha    	
        if ( $http->hasPostVariable( $base . '_ibCaptcha_captchaText_' . $objectAttribute->attribute( 'id' ) ) ) {
        	$postCaptchaText    = $http->postVariable( $base . '_ibCaptcha_captchaText_' . $objectAttribute->attribute( 'id' ) );
			$sessionCaptchaText = $http->sessionVariable( 'IBCaptcha_' . $objectAttribute->attribute( 'id' ) );
			
			if( strtolower( $postCaptchaText ) == strtolower( $sessionCaptchaText ) ) {				
				return eZInputValidator::STATE_ACCEPTED;	
			} else {
				$objectAttribute->setValidationError( 
					ezi18n( 'ibcaptcha/errors', 'Secure code isn`t correct.' )
				);
				return eZInputValidator::STATE_INVALID;					
			}					
        } else {
        	$objectAttribute->setValidationError(
				ezi18n( 'ibcaptcha/errors', 'Missing secure code.' )				
			);
        	return eZInputValidator::STATE_INVALID; 
        }    	
    }

    public function fetchObjectAttributeHTTPInput( $http, $base, $objectAttribute ) {
        if ( $http->hasPostVariable( $base . '_ibCaptcha_captchaText_' . $objectAttribute->attribute( 'id' ) ) ) {
        	$postCaptchaText    = $http->postVariable( $base . '_ibCaptcha_captchaText_' . $objectAttribute->attribute( 'id' ) );
			$sessionCaptchaText = $http->sessionVariable( 'IBCaptcha_' . $objectAttribute->attribute( 'id' ) );
			if( strtolower( $postCaptchaText ) == strtolower( $sessionCaptchaText ) ) {				 
				$objectAttribute->setAttribute( 'data_int', true );
				$http->removeSessionVariable( 'IBCaptcha_' . $objectAttribute->attribute( 'id' ) );	
			}
			$objectAttribute->setAttribute( 'data_text', $postCaptchaText );
			$objectAttribute->store();						
			
			return true;					
        } else {
        	return false;
        }		 
    }

    public function validateCollectionAttributeHTTPInput( $http, $base, $objectAttribute ) { 	
        return $this->validateAttributeHTTPInput( $http, $base, $objectAttribute );
	}
    
	public function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $objectAttribute ) {
        if ( $http->hasPostVariable( $base . '_ibCaptcha_captchaText_' . $objectAttribute->attribute( 'id' ) ) ) {        	
			$currentUserID  = eZUser::currentUserID();
			$notUsedUserIDs = explode( 
				IBCaptchaAttributes::getNotUsedUserIDsSeparator() . ' ',
				$objectAttribute->contentClassAttribute()->attribute( 'data_text4' )
			);
			
			if( in_array( $currentUserID, $notUsedUserIDs ) ) {
				//Check users for whom captcha will not be used
				$collectionAttribute->setAttribute( 'data_int', true );
				$collectionAttribute->setAttribute( 'data_text', 'skipped' );
			} else {
				//Check inputed captcha
	        	$postCaptchaText    = $http->postVariable( $base . '_ibCaptcha_captchaText_' . $objectAttribute->attribute( 'id' ) );
				$sessionCaptchaText = $http->sessionVariable( 'IBCaptcha_' . $objectAttribute->attribute( 'id' ) );
				if( strtolower( $postCaptchaText ) == strtolower( $sessionCaptchaText ) ) {				 
					$collectionAttribute->setAttribute( 'data_int', true );					
				}
				$collectionAttribute->setAttribute( 'data_text', $postCaptchaText );				
			}					
						
			$collectionAttribute->store();			
			
			return true;					
        } else {
        	return false;
        }
    }

	public function isInformationCollector() {
		return true;
	}
	
    public function hasObjectAttributeContent( $contentObjectAttribute ) {
        return true;
    }	
}

eZDataType::register( IBCaptchaType::DATATYPE_STRING, "IBCaptchaType" );