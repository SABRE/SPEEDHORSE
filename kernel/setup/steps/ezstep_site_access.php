<?php
//
// Definition of eZStepSiteAccess class
//
// Created on: <12-Aug-2003 17:35:47 kk>
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


/*!
  \class eZStepSiteAccess ezstep_site_access.php
  \brief The class eZStepSiteAccess does

*/

class eZStepSiteAccess extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteAccess( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_access', 'Site access' );
    }

    function processPostData()
    {
        $accessType = null;
        if( $this->Http->hasPostVariable( 'eZSetup_site_access' ) )
        {
            $accessType = $this->Http->postVariable( 'eZSetup_site_access' );
        }
        else
        {
            return false; // unknown error
        }

        $siteType = $this->chosenSiteType();

        $siteType['access_type'] = $accessType;

        $this->setAccessValues( $siteType );

        $this->storeSiteType( $siteType );
        return true;
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $siteType = $this->chosenSiteType();

            $accessType = $data['Access'];
            if ( in_array( $accessType,
                           array( 'url', 'port', 'hostname' ) ) )
            $siteType['access_type'] = $accessType;

            $this->setAccessValues( $siteType );
            $this->storeSiteType( $siteType );
            return $this->kickstartContinueNextStep();
        }

        $siteType = $this->chosenSiteType();

        // If windows installer, install using url site access
        if ( eZSetupTestInstaller() == 'windows' )
        {
            $siteType['access_type'] = 'url';
            $siteType['access_type_value'] = $siteType['identifier'];
            $siteType['admin_access_type_value'] = $siteType['identifier'] . '_admin';

            $this->storeSiteType( $siteType );

            return true;
        }

        if ( !isset( $siteType['access_type'] ) )
            $siteType['access_type'] = 'url';

        $this->storeSiteType( $siteType );
        return false; // Always show site access
    }

    function display()
    {
        $siteType = $this->chosenSiteType();
        $this->Tpl->setVariable( 'site_type', $siteType );
//         $this->Tpl->setVariable( 'error', $this->Error );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_access.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Site access' ),
                                        'url' => false ) );
        return $result;
    }

    function setAccessValues( &$siteType )
    {
        $accessType = $siteType['access_type'];
        if ( $accessType == 'url' )
        {
            $siteType['access_type_value'] = $siteType['identifier'];
            $siteType['admin_access_type_value'] = $siteType['identifier'] . '_admin';
        }
        else if ( $accessType == 'port' )
        {
            $siteType['access_type_value'] = 8080;        // default port values
            $siteType['admin_access_type_value'] = 8081;
        }
        else if ( $accessType == 'hostname' )
        {
            $siteType['access_type_value'] = $siteType['identifier'] . '.' . eZSys::hostName();
            $siteType['admin_access_type_value'] = $siteType['identifier'] . '-admin.' . eZSys::hostName();
        }
        else
        {
            $siteType['access_type_value'] = $accessType;
            $siteType['admin_access_type_value'] = $accessType . '_admin';
        }
    }
}

?>
