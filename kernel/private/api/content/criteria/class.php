<?php
/**
 * File containing the ezpContentClassCriteria class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package API
 */

/**
 * This class allows for configuration of a content class based criteria
 * @package API
 */
class ezpContentClassCriteria implements ezpContentCriteriaInterface
{
    /**
     * Sets a class name criteria.
     * Content will only be accepted if it is of the given class
     *
     * @param string $contentClassName
     * @return ezpContentClassCriteria
     */
    public function is( $contentClassName )
    {
        $this->classes[] = $contentClassName;
        return $this;
    }

    public function translate()
    {
        return array(
            'type' => 'param',
            'name' => array( 'ClassFilterType', 'ClassFilterArray' ),
            'value' => array( 'include', $this->classes ),
        );
    }

    public function __toString()
    {
        if ( count( $this->classes ) == 0 )
            return 'N/A';
        elseif ( count( $this->classes ) == 1 )
            return "Content class is {$this->classes[0]}";
        else
            return "Content class is one of " .
                join( ', ', array_slice( $this->classes, 0, -1 ) ) .
                " or " . join( '', array_slice( $this->classes, 0, -1 ) );
    }

    public $classes = array();
}
?>
