<?php
//
// Definition of eZExpiryHandler class
//
// Created on: <28-Feb-2003 16:52:53 amos>
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

/**
 * Keeps track of expiry keys and their timestamps
 * @class eZExpiryHandler ezexpiryhandler.php
 */
class eZExpiryHandler
{
    /**
     * Constructor
     */
    function eZExpiryHandler()
    {
        $this->Timestamps = array();
        $this->IsModified = false;

        $cacheDirectory = eZSys::cacheDirectory();
        $this->CacheFile = eZClusterFileHandler::instance( $cacheDirectory . '/' . 'expiry.php' );
        $this->restore();
    }

    /**
     * Load the expiry timestamps from cache
     *
     * @return void
     */
    function restore()
    {
        $Timestamps = $this->CacheFile->processFile( array( $this, 'fetchData' ) );
        $this->Timestamps = $Timestamps;
        $this->IsModified = false;
    }

    /**
     * Includes the expiry file and extracts the $Timestamps variable from it.
     * @param string $path
     */
    static function fetchData( $path )
    {
        include( $path );
        return $Timestamps;
    }

    /**
     * Stores the current timestamps values to cache
     */
    function store()
    {
        if ( $this->IsModified )
        {
            $this->CacheFile->storeContents( "<?php\n\$Timestamps = " . var_export( $this->Timestamps, true ) . ";\n?>", 'expirycache', false, true );
            $this->IsModified = false;
        }
    }

    /**
     * Sets the expiry timestamp for a key
     *
     * @param string $name Expiry key
     * @param int    $value Expiry timestamp value
     */
    function setTimestamp( $name, $value )
    {
        $this->Timestamps[$name] = $value;
        $this->IsModified = true;
    }

    /**
     * Checks if an expiry timestamp exist
     *
     * @param string $name Expiry key name
     *
     * @return bool true if the timestamp exists, false otherwise
     */
    function hasTimestamp( $name )
    {
        return isset( $this->Timestamps[$name] );
    }

    /**
     * Returns the expiry timestamp for a key
     *
     * @param string $name Expiry key
     *
     * @return int|false The timestamp if it exists, false otherwise
     */
    function timestamp( $name )
    {
        if ( !isset( $this->Timestamps[$name] ) )
        {
            eZDebug::writeError( "Unknown expiry timestamp called '$name'", __METHOD__ );
            return false;
        }
        return $this->Timestamps[$name];
    }

    /**
     * Returns the expiry timestamp for a key, or a default value if it isn't set
     *
     * @param string $name Expiry key name
     * @param int $default Default value that will be returned if the key isn't set
     *
     * @return mixed The expiry timestamp, or $default
     */
    static function getTimestamp( $name, $default = false )
    {
        $handler = eZExpiryHandler::instance();
        if ( !isset( $handler->Timestamps[$name] ) )
        {
            return $default;
        }
        return $handler->Timestamps[$name];
    }

    /**
     * Returns a shared instance of the eZExpiryHandler class
     *
     * @return eZExpiryHandler
     */
    static function instance()
    {
        if ( !isset( $GLOBALS['eZExpiryHandlerInstance'] ) ||
             !( $GLOBALS['eZExpiryHandlerInstance'] instanceof eZExpiryHandler ) )
        {
            $GLOBALS['eZExpiryHandlerInstance'] = new eZExpiryHandler();
        }

        return $GLOBALS['eZExpiryHandlerInstance'];
    }

    /**
     * Checks if a shared instance of eZExpiryHandler exists
     *
     * @return bool true if an instance exists, false otherwise
     */
    static function hasInstance()
    {
        return isset( $GLOBALS['eZExpiryHandlerInstance'] ) && $GLOBALS['eZExpiryHandlerInstance'] instanceof eZExpiryHandler;
    }

    /**
     * Called at the end of execution and will store the data if it is modified.
     */
    static function shutdown()
    {
        if ( eZExpiryHandler::hasInstance() )
        {
            eZExpiryHandler::instance()->store();
        }
    }

    /**
     * Registers the shutdown function.
     * @see eZExpiryHandler::shutdown()
     */
    public static function registerShutdownFunction(){
        if ( !eZExpiryHandler::$isShutdownFunctionRegistered ) {
            register_shutdown_function( array('eZExpiryHandler', 'shutdown') );
            eZExpiryHandler::$isShutdownFunctionRegistered = true;
        }
    }

    /**
     * Returns the data modification status
     *
     * @return bool true if data was modified, false if it wasn't
     * @deprecated 4.2 will be removed in 4.3
     */
    public function isModified()
    {
        return $this->IsModified;
    }

    /**
     * Indicates if thre shutdown function has been registered
     * @var bool
     */
    private static $isShutdownFunctionRegistered = false;

    /**
     * Holds the expiry timestamps array
     * @var array
     */
    public $Timestamps;

    /**
     * Wether data has been modified or not
     * @var bool
     */
    public $IsModified;
}

?>
