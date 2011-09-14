<?php
//
// Definition of eZDiffContent class
//
// Created on: <24-Jul-2007 13:45:21 hovik>
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
  eZDiffContent class
*/

/*!
  \class eZDiffContent ezdiffcontent.php
  \ingroup eZDiff
  \brief eZDiff provides an interface for accessing changes in an eZContentObject

  eZDiffContent holds container structures for viewing and accessing detected differences
  in an eZContentObject. This is an abstract class.
*/

class eZDiffContent
{
    /*!
      \public
      Return the set of changes.
    */
    function getChanges()
    {
        return $this->Changeset;
    }

    /*!
      \public
      Returns the old stored content
    */
    function getOldContent()
    {
        return $this->OldContent;
    }

    /*!
      \public
      Returns the new stored content
    */
    function getNewContent()
    {
        return $this->NewContent;
    }

    /*!
      \public
      Sets the old stored content
    */
    function setOldContent( $data )
    {
        $this->OldContent = $data;
    }

    /*!
      \public
      Sets the new stored content
    */
    function setNewContent( $data )
    {
        $this->NewContent = $data;
    }

    /*!
      \public
      Set the changeset array
    */
    function setChanges( $data )
    {
        $this->Changeset = $data;
    }


    function attributes()
    {
        return array( 'changes',
                      'old_content',
                      'new_content' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $attrName )
    {
        switch ( $attrName )
        {
            case 'changes':
            {
                return $this->getChanges();
            }break;

            case 'old_content':
            {
                return $this->getOldContent();
            }break;

            case 'new_content':
            {
                return $this->getNewContent();
            }break;

            default:
            {
                eZDebug::writeError( "Attribute '$attrName' does not exist", 'eZDiffContent' );
                return null;
            }break;
        }
    }

    /// \privatesection
    /// The set of detected changes
    public $Changeset;

    /// Old Object
    public $OldContent;

    /// New Object
    public $NewContent;
}
?>
