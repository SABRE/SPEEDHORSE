<?php
//
// Definition of eZNetSOAPSyncClient class
//
// Created on: <27-Feb-2006 16:57:33 hovik>
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

/*! \file eznetsoapsyncclient.php
*/

/*!
  \class eZNetSOAPSyncClient eznetsoapsyncclient.php
  \brief The class eZNetSOAPSyncClient does

*/


class eZNetSOAPSyncClient extends eZNetSOAPSync
{
    /*!
     Constructor

     \param eZPersistenceObject definition
    */
    function eZNetSOAPSyncClient( $definition = false , $remoteHost = false )
    {
        $this->eZNetSOAPSync( $definition, $remoteHost );
    }

    /*!
     Syncronize - push. Push client data to server.
    */
    function syncronizePushClient( $soapClient )
    {
        $sendCount = 0;

        // 1. Get remote eZ Publish hostID
        $request = new eZSOAPRequest( 'hostID', eZNetSOAPSync::SYNC_NAMESPACE );
        $response = $soapClient->send( $request );

        if( !$request ||
            $response->isFault() )
        {
            eZDebug::writeError( 'Did not get valid result running SOAP method : hostID, on class : ' . $this->ClassName );
            return false;
        }

        $this->RemoteHost = $response->value(); // Missing message IDs

        if ( !$this->RemoteHost )
        {
            eZDebug::writeError( 'RemoteHost not set: ' . var_export( $this->RemoteHost, 1 ),
                                 'eZNetSOAPSync::syncronize()' );
            return false;
        }

        $sendList = $this->createSendArrayList( $soapClient );

        while( $sendList &&
               count( $sendList  ) > 0 )
        {
            $request = new eZSOAPRequest( 'importElements', eZNetSOAPSync::SYNC_NAMESPACE );
            $request->addParameter( 'className', $this->ClassName );
            $request->addParameter( 'hostID', eZNetUtils::hostID() );
            $request->addParameter( 'data', $sendList );
            $response = $soapClient->send( $request );

            if( $response->isFault() )
            {
                eZDebug::writeNotice( 'Did not get valid result running SOAP method : importElements, on class : ' . $this->ClassName );
                return false;
            }

            $sendCount += count( $sendList );

            $sendList = $this->createSendArrayList( $soapClient );
        }

        return array( 'class_name' => $this->ClassName,
                      'export_count' => $sendCount );
    }

    /*!
     Get list of objects to send.

     \param soap client.

     \return list of objects to send.
    */
    function createSendArrayList( $soapClient )
    {
        $useModified = isset( $this->Fields['modified'] );

        if ( $useModified )
        {
            $request = new eZSOAPRequest( 'getLatestModified', eZNetSOAPSync::SYNC_NAMESPACE );
            $request->addParameter( 'className', $this->ClassName );
            $request->addParameter( 'hostID', eZNetUtils::hostID() );
            $response = $soapClient->send( $request );

            if( $response->isFault() )
            {
                eZDebug::writeNotice( 'Did not get valid result running SOAP method : getLatestModified, on class : ' . $this->ClassName );
                return false;
            }

            $latestModified = $response->value();

            $latestList = $this->fetchListByLatestModified( $latestModified );
        }
        else
        {
            $request = new eZSOAPRequest( 'getLatestID', eZNetSOAPSync::SYNC_NAMESPACE );
            $request->addParameter( 'className', $this->ClassName );
            $request->addParameter( 'hostID', eZNetUtils::hostID() );
            $response = $soapClient->send( $request );

            if( $response->isFault() )
            {
                eZDebug::writeNotice( 'Did not get valid result running SOAP method : getLatestID, on class : ' . $this->ClassName );
                return false;
            }

            $latestID = $response->value(); // Missing message IDs

            $latestList = $this->fetchListByLatestID( $latestID );
        }

        return $latestList;
    }

    /*!
     Create standard soap request for "Fetch by latest"

     \param optional number of objects to be fetched at one time, default 100

     \return Soap request
    */
    function createFetchListSoapRequest( $limit = 100, $cli = null )
    {
        $useModified = isset( $this->Fields['modified'] );
        $cli = $cli === null ? eZCLI::instance() : $cli;

        if ( $useModified )
        {
            $request = new eZSOAPRequest( 'fetchListByHostIDLatestModified', eZNetSOAPSync::SYNC_NAMESPACE );
            $request->addParameter( 'className', $this->ClassName );
            $request->addParameter( 'hostID', eZNetUtils::hostID() );
            $request->addParameter( 'latestModified', $this->getLatestModified() );
            $cli->output( 'Synchronizing: ' . $this->ClassName . ', latest updated: ' . $this->getLatestModified() );
        }
        else
        {
            $request = new eZSOAPRequest( 'fetchListByHostIDLatestID', eZNetSOAPSync::SYNC_NAMESPACE );
            $request->addParameter( 'className', $this->ClassName );
            $request->addParameter( 'hostID', eZNetUtils::hostID() );
            $request->addParameter( 'latestID', $this->getLatestID() );
            $cli->output( 'Synchronizing: ' . $this->ClassName . ', id: ' . $this->getLatestID() );
        }

        return $request;
    }

}

?>
