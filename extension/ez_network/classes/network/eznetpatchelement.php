<?php 

/**
 * eZNetPatchElement class implementation
 * 
 */
class eZNetPatchElement
{
    /**
     * Holds a cache directory path
     * 
     * @var string
     */
    protected $cacheDir;

    /**
     * Constructor
     * 
     * @param string $cacheDir
     */
    public function __construct( $cacheDir )
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * Returns translated XML data to array
     * 
     * @return array
     */
    public function data()
    {
        return $this->parse();
    }

    /**
     * Returns text patch installation instructions
     * 
     * @return string
     */
    public function instructions()
    {
        return $this->asText();
    }
}

?>
