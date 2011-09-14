<?php
//
// Created on: <07-May-2003 15:37:09 bf>
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

$offset = $Params['Offset'];

$doFiltration = false;
$filterString = '';

if ( !is_numeric( $offset ) )
    $offset = 0;

if ( $http->hasVariable( 'filterString' ) )
{
    $filterString = $http->variable('filterString');
    if ( ( strlen( trim( $filterString ) ) > 0 ) )
        $doFiltration = true;
}


$ini = eZINI::instance();
$tpl = eZTemplate::factory();

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$overrideArray = eZTemplateDesignResource::overrideArray( $siteAccess );

$mostUsedOverrideArray = array();
$filteredOverrideArray = array();
$mostUsedMatchArray = array( 'node/view/', 'content/view/embed', 'pagelayout.tpl', 'search.tpl', 'basket' );
foreach ( array_keys( $overrideArray ) as $overrideKey )
{
    foreach ( $mostUsedMatchArray as $mostUsedMatch )
    {
        if ( strpos( $overrideArray[$overrideKey]['template'], $mostUsedMatch ) !== false )
        {
            $mostUsedOverrideArray[$overrideKey] = $overrideArray[$overrideKey];
        }
    }
    if ( $doFiltration ) {
        if ( strpos( $overrideArray[$overrideKey]['template'], $filterString ) !== false )
        {
            $filteredOverrideArray[$overrideKey] = $overrideArray[$overrideKey];
        }
    }
}

$tpl->setVariable( 'filterString', $filterString );

if ( $doFiltration )
{
    $tpl->setVariable( 'template_array', $filteredOverrideArray );
    $tpl->setVariable( 'template_count', count( $filteredOverrideArray ) );
}
else
{
    $tpl->setVariable( 'template_array', $overrideArray );
    $tpl->setVariable( 'template_count', count( $overrideArray ) );
}

$tpl->setVariable( 'most_used_template_array', $mostUsedOverrideArray );
$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( "design:visual/templatelist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/design', 'Template list' ) ) );

?>
