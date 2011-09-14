<?php
//
// Definition of eZDiffMatrix class
//
// <05-Apr-2006 14:42:42>
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
  eZDiffMatrix class
*/

/*!
  \class eZDiffMatrix ezdiffmatrix.php
  \ingroup eZDiff
  \biref This class will store values concerned with diff data

  The eZDiffMatrix class will avoid storing 0, which for a large matrix will save
  memory.
*/

class eZDiffMatrix
{

    /*!
      Constructor
    */
    function eZDiffMatrix( $rows = null, $cols = null)
    {
        if ( isset( $rows ) && is_numeric( $rows ) )
            $this->Rows = $rows;

        if ( isset( $cols ) && is_numeric( $cols ) )
            $this->Cols = $cols;
    }

    /*!
      \public
      Sets the dimensions of the matrix
    */
    function setSize( $nRows, $nCols )
    {
        $this->Rows = $nRows;
        $this->Cols = $nCols;
    }

    /*!
      \public
      This method will set (\a $row, \a $col) in the matrix to \a $value, if
      it is not zero.
    */
    function set( $row, $col, $value )
    {
        if ( $value !== 0 )
        {
            $pos = $row * $this->Cols + $col;
            $pos = base_convert( $pos, 10, 36 );
            $this->Matrix["*$pos"] = $value;
        }
    }

    /*!
      \public
      This method will return the value at position (\a $row, \a $col)
    */
    function get( $row, $col )
    {
        $pos = $row * $this->Cols + $col;
        $pos = base_convert( $pos, 10, 36 );
        return isset( $this->Matrix["*$pos"] ) ? $this->Matrix["*$pos"] : 0;
    }

    ///\privatesection
    /// Internal array, holding necessary values.
    public $Matrix = array();

    /// Internal variable, width of the matrix.
    public $Cols;

    /// Internal variable, height of the matrix.
    public $Rows;
}

?>
