<?php
/**
 * File containing logic of activate view
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

require_once( 'kernel/common/template.php' );
$tpl = templateInit();
$module = $Params['Module'];

if ( $module->isCurrentAction( 'Redirect' ) )
{
    $http = eZHTTPTool::instance();
    $redirectURI = $http->variable( 'RedirectURI' );
    $module->redirectTo( $redirectURI );
}
else
{
    $hashString = trim( $Params['HashString'] );
    $subscriptionManager =  new ezcomSubscriptionManager( $tpl, $Params, $module );
    $subscriptionManager->activateSubscription( $hashString );

    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::tr( 'ezcomments/comment/activate', 'Activate subscription' ) ) );
    $Result['content'] = $tpl->fetch( 'design:comment/activate.tpl' );
    return $Result;
}
?>
