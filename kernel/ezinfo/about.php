<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
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

define( 'EZ_ABOUT_CONTRIBUTORS_DIR', 'var/storage/contributors' );
define( 'EZ_ABOUT_THIRDPARTY_SOFTWARE_FILE', 'var/storage/third_party_software.php' );


/*!
  Returns list of contributors;
  Searches all php files in \a $pathToDir and tries to fetch contributor's info
*/
function getContributors( $pathToDir )
{
    $contribFiles = eZDir::recursiveFind( $pathToDir, "php" );
    $contributors = array();
    if ( count( $contribFiles ) )
    {
        foreach ( $contribFiles as $contribFile )
        {
            include_once( $contribFile );
            if ( !isset( $contributorSettings ) )
                continue;

            $tmpFiles = explode( ',', $contributorSettings['files'] );
            $updatedFiles = array();
            foreach ( $tmpFiles as $file )
            {
                if ( trim( $file ) )
                    $updatedFiles[] = trim( $file,"\n\r" );
            }
            $files = implode( ', ', $updatedFiles );
            $contributorSettings['files'] = $files;
            $contributors[] = $contributorSettings;
        }
    }
    return $contributors;
}

/*!
  Returns third-party software from \a $pathToFile
*/
function getThirdPartySoftware( $pathToFile )
{
    if ( !file_exists( $pathToFile ) )
        return array();

    include_once( $pathToFile );
    if ( !isset( $thirdPartySoftware ) )
        return array();

    $thirdPartySoftware = array_unique( $thirdPartySoftware );
    return $thirdPartySoftware;
}

/*!
  Returns active extentions info in run-time
*/
function getExtensionsInfo()
{
    $siteINI = eZINI::instance();
    $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
    $selectedExtensionArray       = $siteINI->variable( 'ExtensionSettings', "ActiveExtensions" );
    $selectedAccessExtensionArray = $siteINI->variable( 'ExtensionSettings', "ActiveAccessExtensions" );
    $selectedExtensions           = array_merge( $selectedExtensionArray, $selectedAccessExtensionArray );
    $selectedExtensions           = array_unique( $selectedExtensions );
    $result = array();
    foreach ( $selectedExtensions as $extension )
    {
        $extensionInfo = eZExtension::extensionInfo( $extension );
        if ( $extensionInfo )
            $result[$extension] = $extensionInfo;
    }
    return $result;
}

/*!
  Replaces all occurrences (in \a $subjects) of the search string (keys of \a $searches )
  with the replacement string (values of \a $searches)

  Returns array with replacements
*/
function strReplaceByArray( $searches = array(), $subjects = array() )
{
    $retArray = array();
    foreach( $subjects as $key => $subject )
    {
        if ( is_array( $subject ) )
        {
            $retArray[$key] = strReplaceByArray( $searches, $subject );
        }
        else
        {
            $retArray[$key] = str_replace( array_keys( $searches ), $searches, $subject );
        }
    }
    return $retArray;
}

$ezinfo = eZPublishSDK::version( true );

$whatIsEzPublish = 'eZ Publish is a professional PHP application framework with advanced
CMS (content management system) functionality. As a CMS its most notable
featureis its revolutionary, fully customizable and extendable content
model. Thisis also what makes eZ Publish suitable as a platform for
general PHP  development,allowing you to rapidly create professional
web-based applications.

Standard CMS functionality (such as news publishing, e-commerce and
forums) are already implemented and ready to use. Standalone libraries
can be used for cross-platform database-independent browser-neutral
PHP projects. Because eZ Publish 4 is a web-based application, it can
be accessed from anywhere you have an internet connection.';

$license =
## BEGIN LICENSE INFO ##
'This copy of eZ Publish is distributed under the terms and conditions
of the eZ Proprietary Use License (eZPUL). Briefly summarized, the
eZPUL allows you to run a limited number of eZ Publish siteaccess or
instances. You may also modify the software for your own use (and, if
desired, share your modifications under the GNU General Public License
v2 with others). For the complete legal terms and conditions of your
eZPUL license, see the file named LICENSE in the root directory of
this eZ Publish distribution.';
## END LICENSE INFO ##

$contributors = getContributors( EZ_ABOUT_CONTRIBUTORS_DIR );
$thirdPartySoftware = getThirdPartySoftware( EZ_ABOUT_THIRDPARTY_SOFTWARE_FILE );
$extensions = getExtensionsInfo();

list( $whatIsEzPublish,
      $license,
      $contributors,
      $thirdPartySoftware,
      $extensions ) = strReplaceByArray( array( 'eZ Systems AS' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ Systems as' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ systems AS' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ systems as' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ Publish' => '<a href="http://ez.no/ezpublish">eZ Publish</a>',
                                                'eZ publish' => '<a href="http://ez.no/ezpublish">eZ Publish</a>' ),
                                         array( $whatIsEzPublish, $license, $contributors, $thirdPartySoftware, $extensions ) );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'ezinfo', $ezinfo );
$tpl->setVariable( 'what_is_ez_publish', $whatIsEzPublish );
$tpl->setVariable( 'license', $license );
$tpl->setVariable( 'contributors', $contributors );
$tpl->setVariable( 'third_party_software', $thirdPartySoftware );
$tpl->setVariable( 'extensions', $extensions );

$Result = array();
$Result['content'] = $tpl->fetch( "design:ezinfo/about.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'Info' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'About' ) ) );

?>
