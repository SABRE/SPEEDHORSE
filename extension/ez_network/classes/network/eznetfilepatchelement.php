<?php 

/**
 * eZNetFilePatchElement class implementation
 * 
 */
class eZNetFilePatchElement extends eZNetPatchElement
{
    /**
     * Holds SimpleXMLElement object
     * 
     * @var SimpleXMLElement
     */
    private $xml;
 
    /**
     * Constructor
     * 
     * @param string $cacheDir
     * @param SimpleXMLElement $patchElement
     */
    public function __construct( $cacheDir, SimpleXMLElement $patchElement )
    {
        parent::__construct( $cacheDir );
        $this->xml = $patchElement;
    }

    /**
     * Returns XML data as an array
     * 
     * @return array
     */
    protected function parse()
    {
        $data = array();

        $data['md5_sum_old'] = array();
        foreach ( $this->xml->MD5SumOldList->MD5Sum as $element )
        {
           $data['md5_sum_old'][] = array( 'file' => (string)$element['file'], 'md5sum' => (string)$element['md5sum'] );
        }

        $data['md5_sum_new'] = array();
        foreach ( $this->xml->MD5SumNewList->MD5Sum as $element )
        {
           $data['md5_sum_new'][] = array( 'file' => (string)$element['file'], 'md5sum' => (string)$element['md5sum'] );
        }
        
        $patchContent = (string)$this->xml->PatchContent;
        
        if ( (bool)$this->xml->PatchContent['encoded'] )
        {
            $patchContent = base64_decode( (string)$this->xml->PatchContent );
        }

        $data['patch_content'] = $patchContent;
 
        return $data;
    }

    /**
     * Returns a patch installation instruction text
     * 
     * @return string
     */
    protected function asText()
    {
        $data = $this->data();
        $text = '';

        if ( $data['patch_content'] == '' )
            return $text;

        $text .= "If you are using eZ Publish in a cluster mode, with multiple webservers where the eZ Publish ";
        $text .= "files are installed, then you need to repeat this step on each server.\n\n";

        $text .= "Requires GNU Patch utility. Unix/BSD/MacOS Patch utility is not compatible.\n\n";
        $text .= "Apply following patch (from eZ Publish root directory):\n";
        $text .= "$ patch -p0 < /path/to/the/update/" . $this->storePatchContent( $data['patch_content'] );
        $text .= "\n";

        return $text;
    }

    /**
     * Stores a patch file content
     * 
     * @return string
     */
    private function storePatchContent( $patchContent )
    {
        $filename = uniqid( 'patch_' ) . '.diff';
        $path = $this->cacheDir . '/' . $filename;

        if ( !file_put_contents( $path, $patchContent ) )
            return false;

        return $filename;
    }
}

?>
