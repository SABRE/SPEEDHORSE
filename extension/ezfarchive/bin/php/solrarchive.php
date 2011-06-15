#!/usr/bin/env php
<?php
/**
 * File containing the ezfarchive script
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0
 *
 */

require 'autoload.php';

if ( !function_exists( 'readline' ) )
{
    function readline( $prompt = '' )
        {
            echo $prompt . ' ';
            return trim( fgets( STDIN ) );
        }
}

function microtime_float()
{
    return microtime( true );
}

set_time_limit( 0 );

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "Archive script for Solr backend storage.\n\n" .
                                                        "" .
                                                        "\n" .
                                                        "solrarchive.php"),
                                     'use-session' => true,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );


$solrUpdate = new ezfSolrArchive( $script, $cli );
$solrUpdate->run();

$script->shutdown();

/**
 * Class containing controlling functions for updating the search index.
 */
class ezfSolrArchive
{
    /**
     * Constructor
     *
     * @param eZScript Script instance
     * @param eZCLI CLI instance
     */
    function ezfSolrArchive( eZScript $script, eZCLI $cli )
    {
        $this->Script = $script;
        $this->CLI = $cli;
        $this->Options = null;
    }

    /**
     * Startup and run script.
     */
    public function run()
    {
        $this->Script->startup();

        $this->Options = $this->Script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:|db-driver:][sql][from:][to:][mode:][criteria:]",
                                                    "",
                                                    array( 'db-host' => "Database host",
                                                           'db-user' => "Database user",
                                                           'db-password' => "Database password",
                                                           'db-database' => "Database name",
                                                           'db-driver' => "Database driver",
                                                           'db-type' => "Database driver, alias for --db-driver",
                                                           'sql' => "Display sql queries",
                                                           'from' =>  "Origin Solr index, specify 'default' if it is the main instance",
                                                           'to' => "Destination Solr index",
                                                           'mode' => 'Either copy or move',
                                                           'criteria' => 'Raw Solr filter expression to use for selecting the Solr documents to archive'
                                                           ) );
        $this->Script->initialize();

        // Fix siteaccess, mainly for selecting the correct .ini settings
        $siteAccess = $this->Options['siteaccess'] ? $this->Options['siteaccess'] : false;
        if ( $siteAccess )
        {
            $this->changeSiteAccessSetting( $siteAccess );
        }
        else
        {


            $this->CLI->output( 'You did not specify a siteaccess. The admin siteaccess is a required option in most cases.' );
            $input = readline( 'Are you sure the default siteaccess has all available languages defined? ([y] or [q] to quit )' );
            if ( $input === 'q' )
            {
                $this->Script->shutdown();
                exit();
            }
        }


        $this->runMain();
    }


    /**
     * Run main process
     */
    protected function runMain()
    {
        $startTS = microtime_float();
        $solrINI = eZINI::instance( 'solr.ini' );

        // look up shards
        $defaultURI = $solrINI->variable( 'SolrBase', 'SearchServerURI' );
        $shards = $solrINI->variable( 'SolrBase', 'Shards' );

        if ($this->Options['from'] == 'default')
        {
            $fromSolr = new eZSolrBase( $defaultURI );
        }
        else
        {
            $fromSolr = new eZSolrBase( $shards[$this->Options['from']]);
        }

        $toSolr = new eZSolrBase($shards[$this->Options['to']]);

        $this->CLI->output( 'Archiving with options:' );
        $this->CLI->output( 'from ' . $this->Options['from'] . ' uri = ' . $fromSolr->SearchServerURI );
        $this->CLI->output( 'to ' . $this->Options['to'] . ' uri = ' . $toSolr->SearchServerURI );
        $this->CLI->output( 'mode ' . $this->Options['mode'] );
        $this->CLI->output( 'criteria ' . $this->Options['criteria'] );

        if ( $this->Options['mode'] == 'copy' )
        {
            ezfSolrUtils::copyDocumentsByQuery($fromSolr, $toSolr, 'meta_guid_s', $this->Options['criteria'], array( 'modify_fields' => array(), 'add_fields' => array(), 'suppress_fields' => array() ));
        }
        else
        {
            ezfSolrUtils::moveDocumentsByQuery($fromSolr, $toSolr, 'meta_guid_s', $this->Options['criteria'], array( 'modify_fields' => array(), 'add_fields' => array(), 'suppress_fields' => array() ));

        }



        $this->CLI->output( 'Comitting changes. Please wait ...' );
        $toSolr->commit();
        if ( !($this->Options['mode'] == 'copy') )
        {
            $fromSolr->commit();
        }
        $endTS = microtime_float();

        $this->CLI->output( 'Archiving took ' . ( $endTS - $startTS ) . ' secs ' );

        $this->CLI->output( 'Finished archiving.' );
    }



    protected function initializeDB()
    {
        $dbUser = $this->Options['db-user'] ? $this->Options['db-user'] : false;
        $dbPassword = $this->Options['db-password'] ? $this->Options['db-password'] : false;
        $dbHost = $this->Options['db-host'] ? $this->Options['db-host'] : false;
        $dbName = $this->Options['db-database'] ? $this->Options['db-database'] : false;
        $dbImpl = $this->Options['db-driver'] ? $this->Options['db-driver'] : false;
        $showSQL = $this->Options['sql'] ? true : false;

        $db = eZDB::instance();

        if ( $dbHost or $dbName or $dbUser or $dbImpl )
        {
            $params = array();
            if ( $dbHost !== false )
                $params['server'] = $dbHost;
            if ( $dbUser !== false )
            {
                $params['user'] = $dbUser;
                $params['password'] = '';
            }
            if ( $dbPassword !== false )
                $params['password'] = $dbPassword;
            if ( $dbName !== false )
                $params['database'] = $dbName;
            $db = eZDB::instance( $dbImpl, $params, true );
            eZDB::setInstance( $db );
        }

        $db->setIsSQLOutputEnabled( $showSQL );
    }


    /**
     * Change siteaccess
     *
     * @param string siteacceee name
     */
    protected function changeSiteAccessSetting( $siteaccess )
    {
        global $isQuiet;
        $cli = eZCLI::instance();
        if ( !file_exists( 'settings/siteaccess/' . $siteaccess ) )
        {
            if ( !$isQuiet )
                $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
        }
    }


    /// Vars

    var $CLI;
    var $Script;
    var $Options;
    var $OffsetList;
    var $Executable;
    var $IterateCount = 0;
    var $Limit = 200;
    var $ObjectCount;
}

?>
