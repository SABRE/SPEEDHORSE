<?php
//
// Definition of eZNetSOAPSyncManager class
//
// Created on: <05-Jul-2007 14:12:31 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
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
//     Att: eZ Systems AS Licensing Dept., Klostergata 30, N-3732 Skien, Norway
//
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file eznetsoapsyncmanager.php
*/

/*!
  \class eZNetSOAPSyncManager eznetsoapsyncmanager.php
  \brief The class eZNetSOAPSyncManager manages SOAP syncronizations.
         The class is used by the client to handle SOAP syncronizations.

*/

class eZNetSOAPSyncManager
{
    /// Const
    const DefaultFetchLimit = 100;

    /*!
     Constructor

     \param eZSoapClient
     \param Class syncronization list
     \param CLI
    */
    function eZNetSOAPSyncManager( $soapClient,
                                   $classList,
                                   $cli )
    {
        $this->SOAPClient = $soapClient;
        $this->CLI = $cli;
        $this->ClassList = $classList;
    }

    /*!
     Syncronize the class list provided in the constructor, using the SOAP client provided.
     */
    function syncronize()
    {
        $orderedClassList = eZNetSOAPSyncAdvanced::orderClassListByDependencies( $this->ClassList );
        $reversedClassList = array_reverse( $orderedClassList );

        $db = eZDB::instance();
        $db->begin();

        // Fetch max modified/ID for all classes which should be syncronized.
        $this->CLI->output( 'Fetching max remote values' );
        $maxValueList = array();
        foreach( $reversedClassList as $className )
        {
            $soapSync = new eZNetSOAPSync( call_user_func( array( $className, 'definition' ) ) );
            $maxValueList[$className] = $soapSync->maxRemoteValue( $this->SOAPClient );
        }

        foreach( $orderedClassList as $className )
        {
            $transferCount = 0;
            $transferSuccess = false;

            while( !$transferSuccess &&
                   $transferCount < 3 )
            {
                $messageSync = new eZNetSOAPSync( call_user_func( array( $className, 'definition' ) ) );
                $result = $messageSync->syncronize( $this->SOAPClient,
                                                    $this->fetchLimit( $className ),
                                                    $maxValueList[$className],
                                                    $this->CLI );
                if ( $result )
                {
                    $transferSuccess = true;
                    $this->CLI->output( 'Imported : ' . $result['import_count'] . ' elements to Class : ' . $result['class_name'] );
                }
                else
                {
                    ++$transferCount;
                }
            }
            if ( !$transferSuccess )
            {
                $this->CLI->error( 'Syncronization of: ' . $className . ' failed. Aborting syncronization.' );
                break;
            }
        }

        $db->commit();
    }

    /*!
     Syncronize the client class list provided in the constructor, using the SOAP client provided.
     */
    function syncronizeClient()
    {
        $orderedClassList = eZNetSOAPSyncAdvanced::orderClassListByDependencies( $this->ClassList );
        $reversedClassList = array_reverse( $orderedClassList );

        $db = eZDB::instance();
        $db->begin();

        // Fetch max modified/ID for all classes which should be syncronized.
        $this->CLI->output( 'Fetching max remote values' );
        $maxValueList = array();
        foreach( $reversedClassList as $className )
        {
            $soapSync = new eZNetSOAPSyncClient( call_user_func( array( $className, 'definition' ) ) );
            $maxValueList[$className] = $soapSync->maxRemoteValue( $this->SOAPClient );
        }

        foreach( $orderedClassList as $className )
        {
            $transferCount = 0;
            $transferSuccess = false;

            while( !$transferSuccess &&
                   $transferCount < 3 )
            {
                $messageSync = new eZNetSOAPSyncClient( call_user_func( array( $className, 'definition' ) ) );
                $result = $messageSync->syncronize( $this->SOAPClient,
                                                    $this->fetchLimit( $className ),
                                                    $maxValueList[$className],
                                                    $this->CLI );
                if ( $result )
                {
                    $transferSuccess = true;
                    $this->CLI->output( 'Imported : ' . $result['import_count'] . ' elements to Class : ' . $result['class_name'] );
                }
                else
                {
                    ++$transferCount;
                }
            }
            if ( !$transferSuccess )
            {
                $this->CLI->error( 'Syncronization of: ' . $className . ' failed. Aborting syncronization.' );
                break;
            }
        }

        $db->commit();
    }

    /*!
     \private
     Get list of custom class fetch limits

     \return custom class fetch limits
     */
    static function customClassFetchLimit()
    {
        return array( 'eZNetPatch' => 1 );
    }

    /*!
     \private

     \param class name

     \return fetch limit
    */
    function fetchLimit( $className )
    {
        $customFetchList = $this->customClassFetchLimit();
        return isset( $customFetchList[$className] ) ?
            $customFetchList[$className] :
            eZNetSOAPSyncManager::DefaultFetchLimit;
    }

    /// Class variables
    var $SOAPClient;
    var $CLI;
    var $ClassList;
}

?>
