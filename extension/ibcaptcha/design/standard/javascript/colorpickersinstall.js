function setColorpicker( name, classAttributeID, imgPath ) {
    var charsColorID       = name + classAttributeID;
    var charsColorPickerID = name + 'ColorPicker' + classAttributeID;    
    var backgorundColorPicker = new MooRainbow( charsColorPickerID, {
        'imgPath': imgPath,
        'startColor': new Color( '#' + $( charsColorID ).get( 'value' ) ).rgb,
        'id': charsColorPickerID + 'Div',
		'onChange': function( color ) {
			$( charsColorID ).set( 'value', color.hex.substr( 1 ) );
		},
		'onComplete': function( color ) {
			$( charsColorID ).set( 'value', color.hex.substr( 1 ) );
		}
    });	
    
    $( charsColorID ).set( 'readonly', 'true' );
}

function setAllColorPickers(  classAttributeID ) {
	var imgPath = $( 'ibCaptchaImagesPath' ).get( 'value' ) + 'extension/ibcaptcha/design/standard/images/colorpicker/';
	setColorpicker( 'charsColor', classAttributeID, imgPath );
	setColorpicker( 'bgColor', classAttributeID, imgPath );
	setColorpicker( 'noiseColor', classAttributeID, imgPath );	
}