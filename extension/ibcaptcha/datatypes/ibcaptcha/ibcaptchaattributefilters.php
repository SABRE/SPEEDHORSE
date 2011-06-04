<?php
class IBCaptchaAttributeFilters {
	private function __construct() {
	}
	
	static public function filterNumber( $string, $lowerLimit, $upperLimit, $canBeFloat = false ) {	
		$mask = ( $canBeFloat ) ? '0123456789.' : '0123456789';
        if( strspn( $string, $mask ) != strlen( $string ) ) {        
            return null;
        }        
		
		if( !$canBeFloat ) {
			$string = (int) $string;
		} else {
			$string = (float) $string;	
		}
        
        if( ( $string > $upperLimit ) || ( $string < $lowerLimit ) ) {
            return null;
        } else {
        	return $string;	
        }
	}	
	
	static public function filterLength( $string ) {
		return self::filterNumber( $string, 3, 12 );
	} 

	static public function filterType( $string ) {
		$availableTypes = IBCaptchaAttributes::getCaptchaAvailableTypes();
		return ( isset( $availableTypes[ (int) $string] ) ) ? $string : null;
	}
	
	static public function filterWidth( $string ) {	
		return self::filterNumber( $string, 100, 500 );
	}
	
	static public function filterHeight( $string ) {
		return self::filterNumber( $string, 30, 200 );
	}		

	static public function filterNoiseLevel( $string ) {
		return self::filterNumber( $string, 1, 10, true );
	}

	static public function filterColor( $string ) {
        if( strcspn( strtolower( $string ), '0123456789abcdef' ) ) {        
            return null;
        }
		
		if( strlen( $string ) != 6 ) {
			return null;
		} else {
			return $string;
		}		
	}
	
	static public function filterNotUsedUserIDs( $string ) {		
		$string  = str_replace( ' ', '', $string );
		$userIDs = explode( ',', $string );
		
		$userIDs = array_unique( $userIDs );
		if( count( $userIDs ) == 0 ) {
			return '';
		}
				
		foreach( $userIDs as $key => $userID ) {
			$user = eZUser::fetch( $userID );
			if( !is_object( $user ) ) {
				unset( $userIDs[$key] );	
			}
		}
		
		if( count( $userIDs ) == 0 ) {
			return '';
		}						
		return implode( 
			IBCaptchaAttributes::getNotUsedUserIDsSeparator() . ' ', 
			$userIDs
		);
	}
}
?>