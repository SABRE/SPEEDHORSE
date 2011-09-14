<?php
//
// Definition of eZOETemplateUtils class
//
// Created on: <14-May-2008 18:42:08 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor
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

/*
 Some misc template operators to get access to some of the features in eZ Publish API
 
*/

class eZOETemplateUtils
{
    function eZOETemplateUtils()
    {
    }

    function operatorList()
    {
        return array( 'ezoe_ini_section' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'ezoe_ini_section' => array( 'section' => array( 
                                                       'type' => 'string',
                                                       'required' => true,
                                                       'default' => '' ),
                                                   'file' => array( 
                                                       'type' => 'string',
                                                       'required' => false,
                                                       'default' => 'site.ini' )
                                           ));
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ret = '';

        switch ( $operatorName )
        {
            case 'ezoe_ini_section':
                {                    
                    $ini = eZINI::instance( $namedParameters['file'] );
                    $ret = $ini->hasSection( $namedParameters['section'] );
                } break;
        }
        $operatorValue = $ret;
    }
 
}

?>
