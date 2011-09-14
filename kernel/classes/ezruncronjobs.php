<?php
//
// Created on: <26-Jun-2008 10:16:45 oh>
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
 Class containing helper functions to execute cronjob parts.
*/
class eZRunCronjobs
{
    /*!
     \static
     Function for running a cronjob script.
    */
    static function runScript( $cli, $scriptFile )
    {
        $scriptMutex = new eZMutex( $scriptFile );
        $lockTS = $scriptMutex->lockTS();
        $runScript = false;
        $maxTime = self::maxScriptExecutionTime();
        if ( $lockTS === false )
        {
            if ( $scriptMutex->lock() )
            {
                $scriptMutex->setMeta( 'pid', getmypid() );
                $runScript = true;
            }
            else
            {
                $cli->error( 'Failed to aquire cronjob part lock: ' . $scriptFile );
            }
        }
        // If the cronjob part has been blocked for  2 * self::maxScriptExecutionTime(),
        // force stealing of the cronjob part
        else if ( $lockTS < time() - 2 * $maxTime )
        {
            $cli->output( 'Forcing to steal the mutex lock: ' . $scriptFile );
            $runScript = eZRunCronjobs::stealMutex( $cli, $scriptMutex, true );
        }
        else if ( $lockTS < time() - $maxTime )
        {
            $cli->output( 'Trying to steal the mutex lock: ' . $scriptFile );
            $runScript = eZRunCronjobs::stealMutex( $cli, $scriptMutex );
        }
        else
        {
            $cli->output( 'Cronjob part locked by other process: ' . $scriptMutex->meta( 'pid' ) );
        }
        if ( $runScript )
        {
            global $script;
            global $isQuiet;
            global $cronPart;
            include( $scriptFile );
            $scriptMutex->unlock();
        }
    }

    /**
     * \static
     * Returns the maximum permitted execution time for cronjobs.
     * This may be different per cronjob part (see cronjob.ini).
     * @return execution time in seconds
     */
    static function maxScriptExecutionTime()
    {
        global $cronPart;
        $cronjobIni = eZINI::instance( 'cronjob.ini' );

        $scriptGroup = "CronjobPart-$cronPart";
        if ( $cronPart !== false and $cronjobIni->hasVariable( $scriptGroup, 'MaxScriptExecutionTime' ) )
            return $cronjobIni->variable( $scriptGroup, 'MaxScriptExecutionTime' );
        else
            return $cronjobIni->variable( 'CronjobSettings', 'MaxScriptExecutionTime' );
    }

    /*!
     \static
     \private

     Steal a script mutex

     \param cli
     \param script mutex to steal
     \param force stealing of mutex ( optional, false by default )

     \return true if mutex is stole successfully
    */
    static function stealMutex( $cli, $scriptMutex, $force = false )
    {
        $cli->output( 'Stealing mutex. Old process has run too long.' );
        $oldPid = $scriptMutex->meta( 'pid' );
        if ( $force )
        {
            if ( is_numeric( $oldPid ) &&
                 $oldPid != 0 &&
                 function_exists( 'posix_kill' ) )
            {
                $cli->output( 'Killing process: ' . $oldPid );
                posix_kill( $oldPid, 9 );
            }
        }
        if ( $scriptMutex->steal( $force ) )
        {
            $scriptMutex->setMeta( 'pid', getmypid() );
            return true;
        }
        else
        {
            $cli->error( 'Failed to steal cronjob part lock.' );
        }
        return false;
    }
}

?>
