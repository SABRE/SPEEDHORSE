<?php
//
// Definition of eZProcess class
//
// Created on: <16-Apr-2002 10:53:33 amos>
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
  \class eZProcess ezprocess.php
  \ingroup eZUtils
  \brief Executes php scripts with parameters safely

*/
class eZProcess
{
    static function run( $file, $Params = array(), $params_as_var = false )
    {
        return eZProcess::instance()->runFile( $Params, $file, $params_as_var );
    }

    /*!
     Helper function, executes the file.
     */
    function runFile( $Params, $file, $params_as_var )
    {
        $Result = null;
        if ( $params_as_var )
        {
            foreach ( $Params as $key => $dummy )
            {
                if ( $key != "Params" and
                     $key != "this" and
                     $key != "file" and
                     !is_numeric( $key ) )
                {
                    ${$key} = $Params[$key];
                }
            }
        }

        if ( file_exists( $file ) )
        {
            $includeResult = include( $file );
            if ( empty( $Result ) &&
                 $includeResult != 1 )
            {
                $Result = $includeResult;
            }
        }
        else
            eZDebug::writeWarning( "PHP script $file does not exist, cannot run.",
                                   "eZProcess" );
        return $Result;
    }

    /**
     * Returns a shared instance of the eZProcess class
     *
     * @return eZProcess
     */
    static function instance()
    {
        if ( empty( $GLOBALS['eZProcessInstance'] ) )
        {
            $GLOBALS['eZProcessInstance'] = new eZProcess();
        }
        return $GLOBALS['eZProcessInstance'];
    }
}

?>
