<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: eZ Proprietary Use License v1.0
// NOTICE: >
//   This source file is part of the eZ Publish (tm) CMS and is
//   licensed under the terms and conditions of the eZ Proprietary
//   Use License v1.0 (eZPUL).
// 
//   A copy of the eZPUL was included with the software. If the
//   license is missing, request a copy of the license via email
//   at eZPUL-v1.0@ez.no or via postal mail at
//     Attn: Licensing Dept. eZ Systems AS, Klostergata 30, N-3732 Skien, Norway
// 
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file
*/

function validate( $fields, $type, $spacesAllowed = true )
{
    $validationMessage = '';
    $fieldContainingError = '';
    $validationErrorType = '';
    $hasValidationError = false;
    $fieldNumber = 0;
    foreach( $fields as $fieldName=>$fieldValue )
    {
        if ( $fieldValue == '' )
        {
            $validationErrorType = 'empty';
            $validationMessage = 'Please specify a value';
            $hasValidationError = true;
        }

        if ( $spacesAllowed == false )
        {
            if ( strstr( $fieldValue, " " ) )
            {
                $validationErrorType = 'contain_spaces';
                $validationMessage = 'spaces is not allowed, but field contains spaces';
                $hasValidationError = true;
            }
        }

        if ( !$hasValidationError )
        {
            switch ( $type[$fieldNumber] )
            {
                case 'array':
                    break;
                case 'name':
                    if ( !preg_match( "/^[A-Za-z0-9]*$/", $fieldValue ) )
                    {
                        $validationErrorType = 'not_valid_name';
                        $validationMessage = 'Name contains illegal characters';
                        $hasValidationError = true;
                    }
                    break;
                case 'string':
                    if ( !is_string( $fieldValue ) or
                         ( is_string( $fieldValue ) and is_numeric( $fieldValue ) ) )
                    {
                        $validationErrorType = 'not_string';
                        $validationMessage = 'Field is not a string';
                        $hasValidationError = true;
                    }
                    break;
                case 'numeric':
                    if ( !is_numeric( $fieldValue ) )
                    {
                        $validationErrorType = 'not_numeric';
                        $validationMessage = 'Field is not a numeric';
                        $hasValidationError = true;
                    }
                    break;
            }
        }

        if ( $hasValidationError )
        {
            $fieldContainingError = $fieldName;
            break;
        }
        ++$fieldNumber;
    }
    return array( 'hasValidationError' => $hasValidationError,
                  'fieldContainingError' => $fieldContainingError,
                  'type' => $validationErrorType,
                  'message' => $validationMessage );
}

?>
