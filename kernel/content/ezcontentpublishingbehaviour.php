<?php
/**
 * File containing the ezpContentPublishingBehaviour class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package
 */

/**
 * This class allows for customization of
 * @property bool disableAsynchronousPublishing
 * @property bool isTemporary if set to true, this behaviour will be used once, and will be reset afterwards
 */
class ezpContentPublishingBehaviour extends ezcBaseOptions
{
    public function __construct( array $options = array() )
    {
        $this->disableAsynchronousPublishing = true;
        $this->isTemporary = false;
        parent::__construct( $options );
    }

    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'disableAsynchronousPublishing':
            case 'isTemporary':
                if ( !is_bool( $value ) )
                {
                    throw new ezcBaseValueException( $name, $value, 'bool' );
                }
                $this->properties[$name] = $value;
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }

    /**
     * Sets the publishing behaviour
     *
     * @param ezpContentPublishingBehaviour $behaviour
     * @return void
     */
    public static function setBehaviour( ezpContentPublishingBehaviour $behaviour )
    {
        self::$behaviour = $behaviour;
    }

    /**
     * Get the currently set behaviour. Returns the default one if one ain't set
     *
     * @return ezpContentPublishingBehaviour
     */
    public static function getBehaviour()
    {
        if ( self::$behaviour === null )
        {
            $return = new ezpContentPublishingBehaviour;
        }
        else
        {
            $return = clone self::$behaviour;
            if ( self::$behaviour->isTemporary )
            {
                self::$behaviour = null;
            }
        }
        return $return;
    }

    /**
     * @var ezpContentPublishingBehaviour
     */
    private static $behaviour = null;
}
?>
