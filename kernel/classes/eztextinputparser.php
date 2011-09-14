<?php
//
// Definition of eZTextInputParser class
//
// Created on: <17-Jul-2002 13:02:32 bf>
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
  \class eZTextInputParser eztextinputparser.php

*/

class eZTextInputParser
{
    const CHUNK_TEXT = 1;
    const CHUNK_TAG = 2;

    /*!

    */
    function eZTextInputParser()
    {

    }

    /*!
     Will parse the input text and create an array of the input.
     False will be returned if the parsing
    */
    function parseText( $text )
    {
        $returnArray = array();
        $pos = 0;


        while ( $pos < strlen( $text ) )
        {
            // find the next tag
            $tagStart = strpos( $text, "<", $pos );

            if ( $tagStart !== false )
            {
                if ( ( $tagStart - $pos ) >= 1 )
                {
                    $textChunk = substr( $text, $pos, $tagStart - $pos );
                    $pos += $tagStart - $pos;


                    if ( strlen( trim( $textChunk ) ) != 0 )
                    {
                        $returnArray[] = array( "Type" => eZTextInputParser::CHUNK_TEXT,
                                                "Text" => $textChunk,
                                                "TagName" => "#text" );

                        eZDebug::writeNotice( $textChunk, "New text chunk in input" );
                    }
                }
                // get the tag
                $tagEnd = strpos( $text, ">", $pos );
                $tagChunk = substr( $text, $pos, $tagEnd - $pos + 1 );
                $tagName = preg_replace( "#^\<(.+)?(\s.*|\>)#m", "\\1", $tagChunk );

                // check for end tag
                if ( $tagName[0] == "/" )
                {
                    print( "endtag" );
                }

                $returnArray[] = array( "Type" => eZTextInputParser::CHUNK_TAG,
                                        "TagName" => $tagName,
                                        "Text" => $tagChunk,
                                        );

                $pos += $tagEnd - $pos;
                eZDebug::writeNotice( $tagChunk, "New tag chunk in input" );
            }
            else
            {

                // just plain text in the rest
                $textChunk = substr( $text, $pos, strlen( $text ) );
                eZDebug::writeNotice( $textChunk, "New text chunk in input" );

                if ( strlen( trim( $textChunk ) ) != 0 )
                {
                    $returnArray[] = array( "Type" => eZTextInputParser::CHUNK_TEXT,
                                            "Text" => $textChunk,
                                            "TagName" => "#text"  );
                }

                $pos = strlen( $text );
            }

            $pos++;
        }
        return $returnArray;
    }

    /// Contains the tags found
    public $TagStack = array();

    /// The tags that don't break the text
    public $InlineTags = array( "emphasize", "strong" );

    /// The tags that break the paragraph
    public $BreakTags = array();
}

?>
