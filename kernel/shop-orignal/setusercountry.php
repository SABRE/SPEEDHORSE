<?php
//
// Created on: <25-Apr-2006 17:01:43 vs>
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

$module = $Params['Module'];

if ( $module->isCurrentAction( 'Set' ) && $module->hasActionParameter( 'Country' ) )
{
    $country = $module->actionParameter( 'Country' );
}
elseif ( isset( $Params['Country'] ) )
{
    $country = $Params['Country'];
}
else
{
    $country = null;
}

if ( $country !== null )
{
    eZShopFunctions::setPreferredUserCountry( $country );
    eZDebug::writeNotice( "Set user country to <$country>" );
}
else
{
    eZDebug::writeWarning( "No country chosen to set." );
}

eZRedirectManager::redirectTo( $module, false );

?>
