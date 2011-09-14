<?php
//
// Definition of eZStepSystemFinetune class
//
// Created on: <08-Aug-2003 16:46:32 kk>
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
  \class eZStepSystemCheck ezstep_system_check.php
  \brief The class eZStepSystemCheck does

*/

class eZStepSystemFinetune extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSystemFinetune( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'system_finetune', 'System finetune' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_finetune_button' ) )
        {
            $this->PersistenceList['run_finetune'] = true;
            return false;
        }
        $this->PersistenceList['run_finetune'] = false;
        return true;
    }

    function init()
    {
        if ( !isset( $this->PersistenceList['run_finetune'] ) )
            $this->PersistenceList['run_finetune'] = false;
        if ( $this->PersistenceList['run_finetune'] )
        {
            $criticalTests = eZSetupCriticalTests();
            $optionalTests = eZSetupOptionalTests();
            $testTable = eZSetupTestTable();

            $runResult = eZSetupRunTests( $criticalTests, 'eZSetup:init:system_check', $this->PersistenceList );
            $optionalRunResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:system_check', $this->PersistenceList );
            $this->Results = $runResult['results'];
            $this->Result = $runResult['result'];
            $this->OptionalResults = $optionalRunResult['results'];
            $this->OptionalResult = $optionalRunResult['result'];
            $persistenceData = $runResult['persistence_list'];

            $testsRun = array();
            foreach ( $this->Results as $testResultItem )
            {
                $testsRun[$testResultItem[1]] = $testResultItem[0];
            }

            eZSetupMergePersistenceList( $this->PersistenceList, $persistenceData );

            $this->PersistenceList['tests_run'] = $testsRun;
            $this->PersistenceList['optional_tests_run'] = $testsRun;

            return ( $this->OptionalResult == EZ_SETUP_TEST_SUCCESS );
        }
        return true;
    }

    function display()
    {
        $this->Tpl->setVariable( 'test', array( 'result' => $this->OptionalResult,
                                                         'results' => $this->OptionalResults ) );
        $this->Tpl->setVariable( 'persistence_data', $this->PersistenceList );
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/system_finetune.tpl" );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'System finetuning' ),
                                        'url' => false ) );
        return $result;
    }

    function showMessage()
    {
        return false;
    }

    // Variables for storing results from tests
    public $Result = null;
    public $Results = null;
}

?>
