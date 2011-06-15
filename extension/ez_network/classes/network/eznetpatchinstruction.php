<?php

/**
 * eZNetPatchInstruction class implementation
 */
class eZNetPatchInstruction extends eZNetPatchElementDecorator
{
    /**
     * Holds instruction format
     * 
     * @var string
     */
    private $format;

    /**
     * Constructor
     * 
     * @param string $format
     * @param eZNetPatchElement $patchElement
     */
    public function __construct( $format, eZNetPatchElement $patchElement )
    {
        parent::__construct( $patchElement );
        
        $this->format = $format;
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
     * Returns patch text installation instructions
     * 
     * @return string
     */
    public function instructions()
    {
        return $this->patchElement->instructions();
    }
}

?>
