<?php

class all2eQRCodeClass
{
    function all2eQRCodeClass()
    {}

    static function encode( $type, $text, $width="250", $height="250" )
    {
        $uri = 'http://chart.apis.google.com/chart?cht=qr&chl=';
        $requesturi = '';
        switch( strtolower($type) ){
            case 'url':
            case 'urlto':
            case 'web':
            {
                $requesturi.='url:';
                break;
            }
            case 'sms':
            case 'smsto':
            {
                $requesturi.='smsto:';
                break;
            }
            case 'email':
            case 'mail':
            case 'mailto':
            {
                $requesturi.='mailto:';
                break;
            }
            case 'tel':
            case 'phone':
            {
                $requesturi.='tel:';
                break;
            }
            default:
            {
                break;
            }
        }
        $requesturi.=$text;
        $textraw = rawurlencode($requesturi);
        $erg = $uri.$textraw."&chs=".$width."x".$height;
        return $erg;
    }
}

?>
