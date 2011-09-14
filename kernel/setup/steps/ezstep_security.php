<?php
//
// Definition of eZStepSecurity class
//
// Created on: <13-Aug-2003 10:42:32 kk>
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
  \class eZStepSecurity ezstep_security.php
  \brief The class eZStepSecurity does

*/

class eZStepSecurity extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSecurity( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'security', 'Security' );
    }

    function processPostData()
    {
        return true; // Always continue
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            return $this->kickstartContinueNextStep();
        }

        if ( file_exists( '.htaccess' ) )
        {
            return true;
        }
        return eZSys::indexFileName() == '' ; // If in virtual host mode, continue (return true)
    }

    function display()
    {
        $this->Tpl->setVariable( 'setup_previous_step', 'Security' );
        $this->Tpl->setVariable( 'setup_next_step', 'Registration' );

        $this->Tpl->setVariable( 'path', realpath( '.' ) );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/security.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Securing site' ),
                                        'url' => false ) );
        return $result;
    }
}

?>
