<?php
//
// Definition of eZImageLayer class
//
// Created on: <03-Oct-2002 15:05:09 amos>
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
  \class eZImageLayer ezimagelayer.php
  \ingroup eZImageObject
  \brief Defines a layer in a image object

*/

class eZImageLayer extends eZImageInterface
{
    /*!
     Constructor
    */
    function eZImageLayer( $imageObjectRef = null, $imageObject = null,
                           $width = false, $height = false, $font = false )
    {
        $this->eZImageInterface( $imageObjectRef, $imageObject, $width, $height );
        $this->setFont( $font );
        $this->TemplateURI = 'design:image/layer.tpl';
    }

    /*!
     A definition which tells the template engine which template to use
     for displaying the image.
    */
    function templateData()
    {
        return array( 'type' => 'template',
                      'template_variable_name' => 'layer',
                      'uri' => $this->TemplateURI );
    }

    /*!
     Sets the URI of the template to use for displaying it using the template engine to \a $uri.
    */
    function setTemplateURI( $uri )
    {
        $this->TemplateURI = $uri;
    }

    /*!
     Tries to merge the current layer with the layer \a $lastLayerData
     onto the image object \a $image.
     Different kinds of layer classes will merge layers differently.
    */
    function mergeLayer( $image, $layerData, $lastLayerData )
    {
        $position = $image->calculatePosition( $layerData['parameters'], $this->width(), $this->height() );
        $x = $position['x'];
        $y = $position['y'];
        $imageObject = $this->imageObject();
        if ( $lastLayerData === null )
        {
            $destinationImageObject = $image->imageObjectInternal( false );
            if ( $destinationImageObject === null )
            {
                $isTrueColor = $this->isTruecolor();
                $image->cloneImage( $this->imageObject(), $this->width(), $this->height(),
                                    $isTrueColor );
            }
            else
            {
                $image->mergeImage( $destinationImageObject, $imageObject,
                                    $x, $y,
                                    $this->width(), $this->height(), 0, 0,
                                    $image->getTransparencyPercent( $layerData['parameters'] ) );
            }
        }
        else
        {
            $destinationImageObject = $image->imageObjectInternal();
            $image->mergeImage( $destinationImageObject, $imageObject,
                                $x, $y,
                                $this->width(), $this->height(), 0, 0,
                                $image->getTransparencyPercent( $layerData['parameters'] ) );
        }
    }

    /*!
     Creates a new file layer for the file \a $fileName in path \a $filePath.
    */
    static function createForFile( $fileName, $filePath, $fileType = false )
    {
        $layer = new eZImageLayer();
        $layer->setStoredFile( $fileName, $filePath, $fileType );
        $layer->process();
        return $layer;
    }

    /// \privatesection
    public $TemplateURI;
}

?>
