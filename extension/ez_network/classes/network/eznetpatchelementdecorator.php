<?php

/**
 * eZNetPatchElementDecorator class implementation
 * 
 */
class eZNetPatchElementDecorator
{
    /**
     * Holds eZNetPatchElement object
     * 
     * @var eZNetPatchElement
     */
    protected $patchElement;

    /**
     * Constructor
     * 
     * @param eZNetPatchElement $patchElement
     */
    public function __construct( eZNetPatchElement $patchElement )
    {
        $this->patchElement = $patchElement;
    }

    /**
     * Returns XML data as an array
     * 
     * @return array
     */
    public function data()
    {
        return $this->patchElement->data();
    }

    /**
     * Returns patch installation text instructions
     * 
     * @return string
     */
    public function instructions()
    {
        return $this->patchElement->instructions();
    }
}

?>
