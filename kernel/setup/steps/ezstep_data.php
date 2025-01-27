<?php
//
// eZSetup - init part initialization
//
// Created on: <08-Nov-2002 11:00:54 kd>
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

/*!
  \class eZStepData ezstep_data.php
  \brief Encapsulates information on all steps

*/

class eZStepData
{
    function eZStepData( )
    {
    }

    /*!
      \static
      Get file and class info for specified step

      \param step number or
             step name
      \return array containing file name and class name
    */
    function step( $description )
    {
        if ( is_string( $description ) )
        {
            foreach (  $this->StepTable as $step )
            {
                if ( $step['class'] == $description )
                {
                    return $step;
                }
            }
        }
        else if ( is_int( $description ) )
        {
            if ( isset( $this->StepTable[$description] ) )
            {
                return $this->StepTable[$description];
            }
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \static
     Get nest install step from step array

     \param current step

     \return next step
    */
    function nextStep( $step )
    {
        foreach ( $this->StepTable as $key => $tableStep )
        {
            if ( $step['class'] == $tableStep['class'] )
            {
                return $this->StepTable[++$key];
            }
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \static
     Get previous install step from step array

     \param current step

     \return previous step
    */
    function previousStep( $step )
    {
        if ( is_string( $step ) ){
            foreach ( $this->StepTable as $key => $tableStep )
            {
                if ( $step == $tableStep['class'] )
                {
                    return $this->StepTable[--$key];
                }
            }
        }
        else
        {
            foreach ( $this->StepTable as $key => $tableStep )
            {
                if ( $step['class'] == $tableStep['class'] )
                {
                    return $this->StepTable[--$key];
                }
            }
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \static
     Get setup progress in percent of total number of steps

     \param current step

     \return Percentage of completet setup, step 1 => 0%, final step => 100%
    */
    function progress( $step )
    {
        $totalSteps = 0;
        foreach ( $this->StepTable as $tableStep )
        {
            if ( !isset( $tableStep['count_step'] ) or
                 $tableStep['count_step'] )
                ++$totalSteps;
        }

        $currentStep = 0;
        foreach ( $this->StepTable as $key => $tableStep )
        {
            if ( $step['class'] == $tableStep['class'] )
            {
                break;
            }
            else if ( !isset( $tableStep['count_step'] ) or
                      $tableStep['count_step'] )
                ++$currentStep;
        }

        return (int) ( $currentStep * 100 / ( $totalSteps - 1 ) );
    }

    /// \privatesection
    /// Array contain all steps in the setup wizard
    public $StepTable = array( array( 'file' => 'welcome',
                                   'class' => 'Welcome' ),
                            array( 'file' => 'system_check',
                                   'class' => 'SystemCheck' ),
                            array( 'file' => 'system_finetune',
                                   'class' => 'SystemFinetune' ),
                            array( 'file' => 'email_settings',
                                   'class' => 'EmailSettings' ),
                            array( 'file' => 'database_choice',
                                   'class' => 'DatabaseChoice' ),
                            array( 'file' => 'database_init',
                                   'class' => 'DatabaseInit' ),
                            array( 'file' => 'language_options',
                                   'class' => 'LanguageOptions' ),
                            array( 'file' => 'site_types',
                                   'class' => 'SiteTypes'),
                           // array( 'file' => 'site_packages',
                           //        'class' => 'SitePackages' ),
                            array( 'file' => 'package_language_options',
                                   'class' => 'PackageLanguageOptions' ),
                            array( 'file' => 'site_access',
                                   'class' => 'SiteAccess'),
                            array( 'file' => 'site_details',
                                   'class' => 'SiteDetails'),
                            array( 'file' => 'site_admin',
                                   'class' => 'SiteAdmin'),
                            array( 'file' => 'security',
                                   'class' => 'Security' ),
                            array( 'file' => 'registration',
                                   'class' => 'Registration' ),
                            array( 'file' => 'create_sites',
                                   'class' => 'CreateSites',
                                   'count_step' => false ),
                            array( 'file' => 'final',
                                   'class' => 'Final') );

}

?>
