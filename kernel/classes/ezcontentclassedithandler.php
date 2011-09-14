<?php
//
// Definition of eZContentClassEditHandler class
//
// Created on: <11-Jan-2010 11:56:00 pa>
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

/**
 * Handler for content class editing.
 */
class eZContentClassEditHandler
{

    /**
     * Store the modification made to an eZContentClass.
     *
     * @param eZContentClass Content class to be stored.
     * @param array[eZContentClassAttribute] Attributes of the new content class.
     * @param array Unordered view parameters
     */
    public function store( eZContentClass $class, array $attributes, array &$unorderedParameters )
    {
        $oldClassAttributes = $class->fetchAttributes( $class->attribute( 'id' ), true, eZContentClass::VERSION_STATUS_DEFINED );
        // Delete object attributes which have been removed.
        foreach ( $oldClassAttributes as $oldClassAttribute )
        {
            $attributeExists = false;
            $oldClassAttributeID = $oldClassAttribute->attribute( 'id' );
            foreach ( $class->fetchAttributes( ) as $newClassAttribute )
            {
                if ( $oldClassAttributeID == $newClassAttribute->attribute( 'id' ) )
                {
                    $attributeExists = true;
                    break;
                }
            }
            if ( !$attributeExists )
            {
                foreach ( eZContentObjectAttribute::fetchSameClassAttributeIDList( $oldClassAttributeID ) as $objectAttribute )
                {
                    $objectAttribute->removeThis( $objectAttribute->attribute( 'id' ) );
                }
            }
        }
        $class->storeDefined( $attributes );

        // Add object attributes which have been added.
        foreach ( $attributes as $newClassAttribute )
        {
            $attributeExists = false;
            $newClassAttributeID = $newClassAttribute->attribute( 'id' );
            foreach ( $oldClassAttributes as $oldClassAttribute )
            {
                if ( $newClassAttributeID == $oldClassAttribute->attribute( 'id' ) )
                {
                    $attributeExists = true;
                    break;
                }
            }
            if ( !$attributeExists )
            {
                $newClassAttribute->initializeObjectAttributes( $objects );
            }
        }
    }
}

?>
