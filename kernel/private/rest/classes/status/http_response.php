<?php
/**
 * File containing the ezpRestHttpResponse status object.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

class ezpRestHttpResponse implements ezcMvcResultStatusObject
{
    public $code;
    public $message;

    public function __construct( $code = null, $message = null )
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function process( ezcMvcResponseWriter $writer )
    {
        if ( $writer instanceof ezcMvcHttpResponseWriter )
        {
            $writer->headers["HTTP/1.1 " . $this->code] = $this->message;
        }

        if ( $this->message !== null )
        {
            $writer->headers['Content-Type'] = 'application/json; charset=UTF-8';
            $writer->response->body = json_encode( array( 'error_message' => $this->message ) );
        }
    }
}
?>
