<?php
//
// eZRSSFunctionCollection - RSS Function collection
//
// Created on: <23-Oct-2009 11:11:54 ar>
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

class eZRSSFunctionCollection
{
    /**
     * Checks if there is a valid RSS/ATOM Feed export for a node or not.
     *
     * @param int $nodeID
     * @return bool Return value is inside a array with return value on result, as this is used as template fetch function.
     */
    static function hasExportByNode( $nodeID )
    {
        if ( !$nodeID )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );

        $db = eZDB::instance();
        $res = $db->arrayQuery( "SELECT id FROM ezrss_export WHERE node_id='$nodeID' AND status=" . eZRSSExport::STATUS_VALID );

        return array( 'result' => isset( $res[0] ) ? true : false );
    }

    /**
     * Return valid eZRSSExport object for a specific node if it exists.
     *
     * @param int $nodeID
     * @return eZRSSExport|false Return value is inside a array with return value on result, as this is used as template fetch function.
     */
    static function exportByNode( $nodeID )
    {
        if ( !$nodeID )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );

        $rssExport = eZPersistentObject::fetchObject( eZRSSExport::definition(),
                                                null,
                                                array( 'node_id' => $nodeID,
                                                       'status' => eZRSSExport::STATUS_VALID ),
                                                true );

        return array( 'result' => $rssExport );
    }
}

?>
