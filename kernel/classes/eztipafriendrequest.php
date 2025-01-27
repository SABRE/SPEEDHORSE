<?php
//
// Definition of eZTipafriendRequest class
//
// Created on: <16-Dec-2004 17:25:49 sp>
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
  \class eZTipafriendRequest eztipafriendrequest.php
  \brief The class eZTipafriendRequest does

*/
class eZTipafriendRequest extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZTipafriendRequest( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( 'email_receiver' => array( 'name' => 'EmailReceiver',
                                                                    'datatype' => 'string',
                                                                    'default' => '',
                                                                    'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      'keys' => array( 'email_receiver' ),
                      'class_name' => 'eZTipafriendRequest',
                      'sort' => array( 'created' => 'desc' ),
                      'name' => 'eztipafriend_request' );
    }

    static function create( $receiver )
    {
        $row = array( "email_receiver" => $receiver,
                      "created" => time() );
        return new eZTipafriendRequest( $row );
    }

    static function checkReceiver( $receiver )
    {
        eZTipafriendRequest::cleanup();
        $ini = eZINI::instance();
        $timeFrame = 1;
        if ( $ini->hasVariable( 'TipAFriend', 'TimeFrame' ) )
            $timeFrame = $ini->variable( 'TipAFriend', 'TimeFrame' );
        $time = time() - $timeFrame * 3600;
        $requestsPerTimeframe = 1;
        if ( $ini->hasVariable( 'TipAFriend', 'MaxRequestsPerTimeframe' ) )
            $requestsPerTimeframe = $ini->variable( 'TipAFriend', 'MaxRequestsPerTimeframe' );

        $db = eZDB::instance();
        $receiver = $db->escapeString( $receiver );
        $countResult = $db->arrayQuery( "SELECT count(*) as count
                                         FROM eztipafriend_request
                                         WHERE email_receiver = '$receiver'
                                           AND created > $time " );
        $count = 0;
        if ( isset(  $countResult[0]['count'] ) )
            $count = $countResult[0]['count'];
        if ( $count >= $requestsPerTimeframe )
            return false;
        return true;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function cleanup()
    {
        $ini = eZINI::instance();
        $db = eZDB::instance();
        $timeFrame = 1;
        if ( $ini->hasVariable( 'TipAFriend', 'TimeFrame' ) )
            $timeFrame = $ini->variable( 'TipAFriend', 'TimeFrame' );
        $time = time() - $timeFrame * 3600;

        $db->query( "DELETE FROM eztipafriend_request
                      WHERE created < $time " );
    }

}

?>
