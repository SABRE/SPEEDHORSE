<?php
/**
 * File containing the eZIEEzcConversions class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.2.0
 * @package ezie
 */
interface eZIEEzcConversions
{
    /**
     * Adds a rotate effect filter
     *
     * @param int $angle Rotation angle. Valid range: [0 to 360]
     * @param string $background Background color used for visible background after rotation
     * @return void
     */
    public function rotate( $angle, $background = 'FFFFFF' );

    /**
     * Adds a pixelate effect filter
     *
     * @param int $width
     * @param int $height
     * @param array(int) $region Affected region, as an array of 4 keys: x, y, w, h
     * @return void
     */
    public function pixelate( $width, $height, $region = null );

    /**
     * Adds a horizontal flip filter
     *
     * @param array(int) $region Affected region, as an array of 4 keys: x, y, w, h
     * @return void
     */
    public function horizontalFlip( $region = null );

    /**
     * Adds a vertical flip filter
     *
     * @param array(int) $region Affected region, as an array of 4 keys: x, y, w, h
     * @return void
     */
    public function verticalFlip( $region = null );

    /**
     * Adds a colorspace transformation effect
     *
     * @link http://www.imagemagick.org/script/command-line-options.php?ImageMagick=v34va9glpjbvqkoke9ag5u5283#colorspace
     *
     * @param string $space Target colorspace
     * @param array(int)
     * @return void
     */
    public function colorspace( $space, $region = null );

    /**
     * Adds a brightness effect
     *
     * @param int $value Brightness value. Valid range: [-255 to 255]
     * @return void
     */
    public function brightness( $value, $region = null );

    /**
     * Adds a contrast effect
     *
     * @param int $value Contrast value. Valid range: [-100 to 100]
     * @return void
     */
    public function contrast( $value, $region = null );
}
