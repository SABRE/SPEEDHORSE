<?php
//
// Definition of EZStepWelcome class
//
// Created on: <08-Aug-2003 15:00:02 kk>
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
  \class eZStepWelcome ezstep_welcome.php
  \brief The class eZStepWelcome does

*/
class eZStepWelcome extends eZStepInstaller
{

    /*!
     Constructor
    */
    function eZStepWelcome( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'welcome', 'Welcome' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_finetune_button' ) )
        {
            $this->PersistenceList['run_finetune'] = true;
        }

        if ( $this->Http->hasPostVariable( 'eZSetupWizardLanguage' ) )
        {
            $wizardLanguage = $this->Http->postVariable( 'eZSetupWizardLanguage' );
            $this->PersistenceList['setup_wizard'] = array( 'language' => $wizardLanguage );

            eZTranslatorManager::setActiveTranslation( $wizardLanguage );
        }

        return true;
    }

    function init()
    {
        $optionalTests = eZSetupOptionalTests();
        $testTable = eZSetupTestTable();

        $optionalRunResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:system_check', $this->PersistenceList );
        $this->OptionalResults = $optionalRunResult['results'];
        $this->OptionalResult = $optionalRunResult['result'];

        $testsRun = array();
        if ( isset( $this->Results ) && is_array( $this->Results ) )
        {
            foreach ( $this->Results as $testResultItem )
            {
                $testsRun[$testResultItem[1]] = $testResultItem[0];
            }
        }

        $this->PersistenceList['tests_run'] = $testsRun;
        $this->PersistenceList['optional_tests_run'] = $testsRun;

        return false; // Always show welcome message
    }

    function display()
    {
        $result = array();

        $languages = false;
        $defaultLanguage = false;
        $defaultExtraLanguages = false;

        eZSetupLanguageList( $languages, $defaultLanguage, $defaultExtraLanguages );

        eZTranslatorManager::setActiveTranslation( $defaultLanguage, false );

        $this->Tpl->setVariable( 'language_list', $languages );
        $this->Tpl->setVariable( 'primary_language', $defaultLanguage );
        $this->Tpl->setVariable( 'optional_test', array( 'result' => $this->OptionalResult,
                                                         'results' => $this->OptionalResults ) );
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/welcome.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Welcome to eZ Publish' ),
                                    'url' => false ) );

        return $result;
    }

    function showMessage()
    {
        return true;
    }

}

?>
