<?php
//
// Definition of eZSetupSummary class
//
// Created on: <13-Aug-2003 17:49:28 kk>
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
  \class eZSetupSummary ezsetup_summary.php
  \brief The class eZSetupSummary does

*/

class eZSetupSummary
{
    /*!
     Constructor

     Create new object for generating summary

     \param template
     \param persistence list
    */
    function eZSetupSummary( $tpl, &$persistenceList )
    {
        $this->Tpl =& $tpl;
        $this->PersistenceList =& $persistenceList;
    }

    /*!
    Get summary

    \return Summary
    */
    function summary()
    {
        $databaseMap = eZSetupDatabaseMap();

        $persistenceList = $this->PersistenceList;

        if ( isset( $persistenceList['tests_run'] ) and
             count( $persistenceList['tests_run'] ) > 0 )
        {
            $checkPassed = true;
            foreach ( $persistenceList['tests_run'] as $checkValue )
            {
                if ( $checkValue != 1 )
                {
                    $checkPassed = false;
                    break;
                }
            }
            if ( $checkPassed === true )
                $this->Tpl->setVariable( 'system_check', 'ok' );
            else
                $this->Tpl->setVariable( 'system_check', '' );
        }
        else
        {
            $this->Tpl->setVariable( 'system_check', '' );
        }

        // Image settings
        if ( isset( $persistenceList['imagemagick_program'] ) && $persistenceList['imagemagick_program']['result'] )
        {
            $this->Tpl->setVariable( 'image_processor', 'ImageMagick' );
        }
        else if ( isset( $persistenceList['imagegd_extension'] ) && $persistenceList['imagegd_extension']['result'] )
        {
            $this->Tpl->setVariable( 'image_processor', 'ImageGD' );
        }
        else
        {
            $this->Tpl->setVariable( 'image_processor', '' );
        }

        // Database selected
        if ( isset( $persistenceList['database_info'] ) ) {
            $database = $databaseMap[$persistenceList['database_info']['type']]['name'];
            $this->Tpl->setVariable( 'database', $database );
        }
        else
        {
            $this->Tpl->setVariable( 'database', '' );
        }

        // Languages selected
        if ( isset( $persistenceList['regional_info'] ) ) {
            $languages = $persistenceList['regional_info']['languages'];
            $this->Tpl->setVariable( 'languages', $languages );
        }
        else
        {
            $this->Tpl->setVariable( 'languages', '' );
        }

        // Email settings
        $this->Tpl->setVariable( 'summary_email_info', '' );

        if ( isset( $persistenceList['email_info'] ) ) {
            if ( $persistenceList['email_info']['type'] == 1 )
            {
                $this->Tpl->setVariable( 'summary_email_info', 'sendmail' );
            }
            else if ( $persistenceList['email_info']['type'] == 2 )
            {
                $this->Tpl->setVariable( 'summary_email_info', 'SMTP' );
            }
        }

        // Templates chosen
        $chosenSitePackage = false;
        if ( isset( $this->PersistenceList['chosen_site_package']['0'] ) )
        {
            $chosenSitePackage = $this->PersistenceList['chosen_site_package']['0'];
        }

        $this->Tpl->setVariable( 'site_package', $chosenSitePackage );

        return $this->Tpl->fetch( 'design:setup/summary.tpl' );
    }

    public $Tpl;
    public $PersistenceList;
}

?>
