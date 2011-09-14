<?php
//
// Definition of eZHTTPHeader class
//
// Created on: <24-Nov-2005 12:34:48 hovik>
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
  \class eZHTTPHeader ezhttpheader.php
  \brief The class eZHTTPHeader does

*/

class eZHTTPHeader
{
    /*!
     * \static
     * Returns true if the custom HTTP headers are enabled, false otherwise.
     * The result is cached in memory to save time on multiple invocations.
     */
    static function enabled()
    {
        if ( isset( $GLOBALS['eZHTTPHeaderCustom'] ) )
        {
            return $GLOBALS['eZHTTPHeaderCustom'];
        }

        $ini = eZINI::instance();
        if ( !$ini->hasVariable( 'HTTPHeaderSettings', 'CustomHeader' ) )
        {
            $GLOBALS['eZHTTPHeaderCustom'] = false;
        }
        else
        {
            if ( $ini->variable( 'HTTPHeaderSettings', 'CustomHeader' ) === 'enabled'
                 && $ini->hasVariable( 'HTTPHeaderSettings', 'OnlyForAnonymous' )
                 && $ini->variable( 'HTTPHeaderSettings', 'OnlyForAnonymous' ) === 'enabled' )
            {
                $user = eZUser::currentUser();
                $GLOBALS['eZHTTPHeaderCustom'] = !$user->isLoggedIn();
            }
            else
            {
                $GLOBALS['eZHTTPHeaderCustom'] = $ini->variable( 'HTTPHeaderSettings', 'CustomHeader' ) == 'enabled';
            }
        }

        return $GLOBALS['eZHTTPHeaderCustom'];
    }

    /*!
     \static
     Get Header override array by requested URI
    */
    static function headerOverrideArray( $uri )
    {
        $headerArray = array();

        if ( !eZHTTPHeader::enabled() )
        {
            return $headerArray;
        }

        $contentView = false;

        $uriString = eZURLAliasML::cleanURL( $uri->uriString() );

        // If content/view used, get url alias for node
        if ( strpos( $uriString, 'content/view/' ) === 0 )
        {
            $urlParts = explode( '/', $uriString );
            $nodeID = $urlParts[3];
            if ( !$nodeID )
            {
                return $headerArray;
            }

            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                return $headerArray;
            }

            $uriString = $node->pathWithNames();
            $contentView = true;
        }
        else
        {
            $uriCopy = clone $uri;
            eZURLAliasML::translate( $uriCopy );
            if ( strpos( $uriCopy->uriString(), 'content/view' ) === 0 )
            {
                $contentView = true;
            }
        }

        $uriString = '/' . eZURLAliasML::cleanURL( $uriString );
        $ini = eZINI::instance();

        foreach( $ini->variable( 'HTTPHeaderSettings', 'HeaderList' ) as $header )
        {
            foreach( $ini->variable( 'HTTPHeaderSettings', $header ) as $path => $value )
            {
                $path = '/' . eZURLAliasML::cleanURL( $path );
                if ( strlen( $path ) == 1 &&
                     !$contentView &&
                     $uriString != '/' )
                {
                    continue;
                }

                if ( strpos( $uriString, $path ) === 0 )
                {
                    @list( $headerValue, $depth, $level ) = explode( ';', $value );

                    if ( $header == 'Expires' )
                    {
                        $headerValue = gmdate( 'D, d M Y H:i:s', time() + $headerValue ) . ' GMT';
                    }

                    if ( $depth === null )
                    {
                        $headerArray[$header] = $headerValue;
                    }
                    else
                    {
                        $pathLevel = count( explode( '/', $path ) );
                        $uriLevel = count( explode( '/', $uriString ) );
                        if ( $level === null )
                        {
                            if ( $uriLevel <= $pathLevel + $depth )
                            {
                                $headerArray[$header] = $headerValue;
                            }
                        }
                        else
                        {
                            if ( $uriLevel <= $pathLevel + $depth &&
                                 $uriLevel >= $pathLevel + $level )
                            {
                                $headerArray[$header] = $headerValue;
                            }
                        }
                    }
                }
            }
        }

        return $headerArray;
    }
}

?>
