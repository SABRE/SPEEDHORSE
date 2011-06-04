<?php
class IBCaptcha {
    public function __construct( $params ) {
    	$this->length        = isset( $params['length'] ) ? $params['length'] : 4;
		$this->type          = isset( $params['type'] ) ? $params['type'] : 3;
    	$this->width         = isset( $params['width'] ) ? $params['width'] : 200;
		$this->height        = isset( $params['height'] ) ? $params['height'] : 50;		
		$this->fontSize      = $this->height * 0.50;
		$this->noiseLevel    = isset( $params['noiseLevel'] ) ? $params['noiseLevel'] : 5;
		
		$this->textColorHEX  = isset( $params['textColor'] ) ? $params['textColor'] : 'ffffff';
		$this->bgColorHEX    = isset( $params['bgColor'] ) ? $params['bgColor'] : '0063ff'; 
		$this->noiseColorHEX = isset( $params['noiseColor'] ) ? $params['noiseColor'] : '42ccff';
    }

    private function buildImage() {
        $this->image = imagecreate( $this->width, $this->height );
    }

    private function setColors() {
    	$this->backgroundColor = $this->setColor( $this->bgColorHEX );
        $this->textColor       = $this->setColor( $this->textColorHEX );        
        $this->noiseColor      = $this->setColor( $this->noiseColorHEX );
    }
    
    private function setColor( $colorDecString ) {
    	return imagecolorallocate( 
			$this->image, 
    		hexdec( substr( $colorDecString, 0, 2 ) ),
    		hexdec( substr( $colorDecString, 2, 2 ) ),
    		hexdec( substr( $colorDecString, 4, 2 ) )
		);
    }
    
    private function generateNoise() {
    	$noiseNumber = $this->height * $this->width / 2000;
        for( $i = 0; $i < $noiseNumber; $i++ ) {
        	$radius = $this->noiseLevel * 3 * mt_rand( 1, 5 );
            imagefilledellipse( 
				$this->image, 
				mt_rand( 0, $this->width ), 
				mt_rand( 0, $this->height ), 
				$radius, 
				$radius, 
				$this->noiseColor
			);
        }
    }

    private function drawText() {
        $text    = $this->generateTest();
        $font    = 'extension/ibcaptcha/design/standard/fonts/captcha_font.ttf';
        $textbox = imagettfbbox( $this->fontSize, 0, $font, $text );
        $x = ( $this->width - $textbox[4] )/2;
        $y = ( $this->height - $textbox[5] )/2;
        imagettftext( 
			$this->image,
			$this->fontSize,
			2,
			( $this->width - $textbox[4] ) / 2,
			( $this->height - $textbox[5] ) / 2,
			$this->textColor,
			$font,
			$text
		);
    }
    
    private function generateTest() {
    	$http = eZHTTPTool::instance();
    	if( $this->overwrite || !$http->hasSessionVariable( 'IBCaptcha_' . $this->contentAttributeID ) ) {
    		$charsTable = $this->getCharacterTable();    		
    		$text = null;
			for( $i = 0; $i < $this->length; $i++ ) {
				$text .= $charsTable[ array_rand( $charsTable ) ];
			}			

			$http->setSessionVariable( 
				'IBCaptcha_' . $this->contentAttributeID,
				$text
			);
			return $text;
    	} else {
    		return $http->sessionVariable( 'IBCaptcha_' . $this->contentAttributeID );
    	}   	
    }
    
    private function getCharacterTable() {
    	switch ( $this->type ) {
        	case 1:
        		return array_merge( range( 'A', 'Z' ), range( 0, 9 ) );        	
			case 2:
        		return range( 0, 9 );        	
			case 3:
        		return range( 'A', 'Z' );
        	default:
        		return array_merge( range( 'A', 'Z' ), range( 0, 9 ) );
        }
    }

    private function outputCaptcha() {
        header( 'Content-Type: image/gif' );
        header( 'Cache-Control: no-cache, must-revalidate' );
		header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
        imagegif( $this->image );
        imagedestroy( $this->image );
    }

    public function run( $contentAttributeID, $overwrite = false ) {
    	$this->contentAttributeID = $contentAttributeID;
    	$this->overwrite          = $overwrite;

        $this->buildImage();
        $this->setColors();
        $this->generateNoise();
        $this->drawText();
        $this->outputCaptcha();
    }
}