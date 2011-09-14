<?php
//
// Definition of eZTextTool class
//
// Created on: <04-Jun-2002 09:12:36 bf>
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
  \class eZTextTool eztexttool.php
  \ingroup eZUtils
  \brief eZTextTool is a class with different useful text functions

*/

class eZTextTool
{
    /*!
     \static
     Returns an HTML highlighted and displayable formatted HTML from the
     input text. < and > are converted to &lt; and &gt;
    */
    function highlightHTML( $input )
    {
        $input = str_replace( "<", "&lt;", $input );
        $input = str_replace( ">", "&gt;", $input );

        $input = preg_replace( "#&lt;(.*?)&gt;#m", "<font color='red'>&lt;$1&gt;</font>", $input );

        return $input;
    }

    function highlightPHP()
    {

    }

    function concatDelimited()
    {
        $numargs = func_num_args();
        $argList = func_get_args();
        $text = null;
        if ( $numargs > 1 )
        {
            $delimit = $argList[0];
            $text = implode( $delimit, eZTextTool::arrayFlatten( array_splice( $argList, 1 ) ) );
        }
        return $text;
    }

    function concat()
    {
        $numargs = func_num_args();
        $argList = func_get_args();
        $text = null;
        if ( $numargs > 0 )
        {
            $text = implode( '', eZTextTool::arrayFlatten( $argList ) );
        }
        return $text;
    }

    function arrayFlatten( $array )
    {
        $flatArray = array();
        $expandItems = $array;
        $done = false;
        while ( !$done )
        {
            $checkList = $expandItems;
            $leftOvers = array();
            $foundArray = false;
            foreach ( array_keys( $checkList ) as $key )
            {
                $item = $checkList[$key];
                if ( is_array ( $item ) )
                {
                    $leftOvers = array_merge( $leftOvers, $item );
                    $foundArray = true;
                }
                else
                {
                    if ( $foundArray )
                        $leftOvers[] = $item;
                    else
                        $flatArray[] = $item;
                }
            }
            $expandItems = $leftOvers;
            if ( count( $expandItems ) == 0 )
            {
                $done = true;
            }
        }
        return $flatArray;
    }
}
?>
