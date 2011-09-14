<?php
//
// Definition of eZURLFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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
  \class eZURLFunctionCollection ezurlfunctioncollection.php
  \brief The class eZURLFunctionCollection does

*/

class eZURLFunctionCollection
{
    /*!
     Constructor
    */
    function eZURLFunctionCollection()
    {
    }

    function fetchList( $isValid, $offset, $limit, $onlyPublished )
    {
        $parameters = array( 'is_valid' => $isValid,
                             'offset' => $offset,
                             'limit' => $limit,
                             'only_published' => $onlyPublished );
        $list = eZURL::fetchList( $parameters );
        if ( $list === null )
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        else
            $result = array( 'result' => $list );
        return $result;
    }

    function fetchListCount( $isValid, $onlyPublished )
    {
        $parameters = array( 'is_valid' => $isValid,
                             'only_published' => $onlyPublished );
        $listCount = eZURL::fetchListCount( $parameters );
        if ( $listCount === null )
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        else
            $result = array( 'result' => $listCount );
        return $result;
    }

}

?>
