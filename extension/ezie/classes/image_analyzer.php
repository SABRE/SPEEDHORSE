<?php
/**
 * File containing the eZIEImageAnalyzer class
 * This class overrides ezcImageAnalyzer in order to support the ezpublish cluster constraints
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.2.0
 * @package ezie
 */
class eZIEImageAnalyzer extends ezcImageAnalyzer
{
    /**
     * Constructor overload
     * Creates a local copy of the image so that it can be analyzed
     *
     * @param string $file
     */
    public function __construct( $file )
    {
        $clusterHandler = eZClusterFileHandler::instance( $file );
        $clusterHandler->fetch();

        parent::__construct( $file );

        $clusterHandler->deleteLocal();
    }

    /**
     * Overload of ezcImageAnalyzer::analyzeImage()
     * Creates a temporary local copy of the image file so that it can be analyzed
     *
     * @return void
     */
    public function analyzeImage()
    {
        $clusterHandler = eZClusterFileHandler::instance( $this->filePath );
        $clusterHandler->fetch();

        parent::analyzeImage();

        $clusterHandler->deleteLocal();
    }
}

?>
