<?php
//
// Definition of eZDiff class
//
// <creation-tag>
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
  Diff functionality
*/

/*!
  \class eZDiff ezdiff.php
  \ingroup eZDiff
  \brief eZDiff provides an access point the diff system

  The eZDiff class is responsible for accessing and loading the correct
  diff engine when a datatype makes a call to eZDiff library.
*/
class eZDiff
{
    /*!
      Instantiates the eZDiff object
      \param $diffEngineType The type of diff engine to initialize at start
    */
    function eZDiff( $diffEngineType = false )
    {
        if ( $diffEngineType )
        {
            $this->DiffEngine = $diffEngineType;
            eZDebug::writeNotice( "Diff engine type: " . $diffEngineType, 'eZDiff' );
        }
    }

    /*!
      \public
      Set the diff engine to be used for diffing.
      \param $diffEngineType The type of diff engine to intitialize
    */
    function setDiffEngineType( $diffEngineType )
    {
        if ( isset( $diffEngineType ) )
        {
            $this->DiffEngine = $diffEngineType;
            eZDebug::writeNotice( "Changing diff engine to type: " . $diffEngineType, 'eZDiff' );
        }
    }

    /*!
      \public
      \return The diff engine type used in this instance.
    */
    function getDiffEngineType()
    {
        return $this->DiffEngine;
    }

    /*!
      \public
      \return Internal id of engine type given in \a $typeString
    */
    function engineType( $typeString )
    {
        switch ( $typeString )
        {
            case 'text':
            {
                return $this->DIFF_TYPE['text'];
            }break;

            case 'xml':
            {
                return $this->DIFF_TYPE['xml'];
            }break;

            case 'container':
            {
                return $this->DIFF_TYPE['container'];
            }break;
        }
    }

    /*!
      \public
      Returns diff engine of \a $type
    */
    function initDiffEngine()
    {
        if ( !$diffEngine = $this->DiffEngineInstance )
        {
            switch( $this->DiffEngine )
            {
                case '0': //Text
                {
                    $this->DiffEngineInstance = new eZDiffTextEngine();
                }break;

                case '1': //XML
                {
                    $this->DiffEngineInstance = new eZDiffXMLTextEngine();
                }break;

                case '2': //ObjectContainer
                {
                    $this->DiffEngineInstance = new eZDiffContainerObjectEngine();
                }
            }
        }
    }

    /*!
      \public
      Perform a diff operation on the provided set of data. A valid
      diff engine have to be specified, before this can be run.

      \return An eZDiffContent object with the detected changes
    */
    function &diff( $fromData, $toData)
    {
        if ( !$this->DiffEngineInstance )
            return null;

        $differ =& $this->DiffEngineInstance;
        $diffObject = $differ->createDifferenceObject( $fromData, $toData );
        return $diffObject;
    }

    ///\privatesection
    /// Variable holding the diff engine type
    public $DiffEngine;

    /// The instance of the diff engine class
    public $DiffEngineInstance;

    /// The allowed input on which to diff
    public $DIFF_TYPE = array( 'text' => 0,
                            'xml' => 1,
                            'container' => 2 );
}

?>
