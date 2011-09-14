<?php
//
// Definition of eZNetPatchBase class
//
// Created on: <29-Nov-2006 15:12:57 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
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
//     Att: eZ Systems AS Licensing Dept., Klostergata 30, N-3732 Skien, Norway
//
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file eznetpatchbase.php
*/

/*!
  \class eZNetPatchBase eznetpatchbase.php
  \brief The class eZNetPatchBase does

*/

/*! DEPRECATED, debug_backtrace() for php5 and php4 is different,
    class name will be incorrect if we use this solution.
    OBS - READ THIS !!!!
class A
{
    function kake()
    {
        $backtrace = debug_backtrace();
        echo $backtrace[0]['class'];
        echo " - ";
    }
}

class B extends A
{
}

A::kake();
B::kake();
// outputs : a - b -

The lines:
$backtrace = debug_backtrace();
$className = $backtrace[0]['class'];

all over the place is for getting the correct class called as static functions.

 */
include_once( 'kernel/common/i18n.php' );

class eZNetPatchBase extends eZNetLargeObject
{
    /// Consts
    const StatusAlpha = 0;
    const StatusBeta = 1;
    const StatusRC = 2;
    const StatusFinal = 3;
    const StatusRemoved = 4;
    const StatusSecurity = 5;

    const VersionStatusDraft = 0;
    const VersionStatusPublished = 1;

    const RequiredNone = -1;


   /*!
     Constructor
    */
    function eZNetPatchBase( $rows = array() )
    {
        $this->NetUtils = new eZNetUtils();
        $this->eZNetLargeObject( $rows );
    }

    /*!
     \abstract
     \reimp
    */
    static function definition()
    {
    }

    /*!
    \reimp
    */
    function attribute( $attr, $noFunction = false )
    {
        $retVal = null;
        switch( $attr )
        {
            case 'patch_text_diff':
            {
                $retVal = '';
                $domDocument = new DOMDocument( '1.0', 'utf-8' );
                if ( $domDocument->loadXML( $this->attribute( 'ez_patch' ) ) )
                {
                    if ( $rootNode = $domDocument->documentElement )
                    {
                        if ( $patchElementNodeList = $rootNode->getElementsByTagName( 'PatchElement' ) )
                        {
                            foreach( $patchElementNodeList as $patchElement )
                            {
                                if ( $patchElement->getAttribute( 'type' ) == 'patch' )
                                {
                                    $retVal = eZNetPatchBase::patchNodeContent( $patchElement );
                                    break;
                                }
                            }
                        }
                    }
                }
            } break;

            case 'ez_patch':
            {
                $retVal = base64_decode( $this->attribute( 'filedata' ) );
            } break;

            case 'patch_scripts':
            {
                $retVal = '';
                $domDocument = new DOMDocument( '1.0', 'utf-8' );
                if ( $domDocument->loadXML( $this->attribute( 'ez_patch' ) ) )
                {
                    if ( $rootNode = $domDocument->documentElement )
                    {
                        if ( $patchElementNodeList = $rootNode->getElementsByTagName( 'PatchElement' ) )
                        {
                            foreach( $patchElementNodeList as $patchElement )
                            {
                                if ( $patchElement->getAttribute( 'type' ) == 'script' )
                                {
                                    $retVal = eZNetPatchBase::patchScriptContent( $patchElement );
                                    break;
                                }
                            }
                        }
                    }
                }
            } break;

            case 'option_array':
            {
                $optionDef = $this->attribute( 'options' );
                $retVal = $optionDef == '' ? array() : unserialize( $optionDef );
            } break;

            case 'required_patch':
            {
                $className = get_class( $this );
                $retVal = call_user_func_array( array( $className, 'fetchObject' ),
                                                array( $this->definition(),
                                                       null,
                                                       array( 'id' => $this->attribute( 'required_patch_id' ),
                                                              'version_status' => $this->attribute( 'version_status' ) ),
                                                       true ) );
            } break;

            default:
            {
                $retVal = eZNetLargeObject::attribute( $attr );
            } break;
        }

        return $retVal;
    }

    /*!
     \static
     Get patch node content from DOM Node

     \param Patch Element dom node

     \return patch content.
    */
    static function patchNodeContent( DOMElement $patchNode )
    {
        $patchContent = '';
        $encoded = false;

        $contentNode = $patchNode->getElementsByTagName( 'PatchContent' )->item( 0 );
        $patchContent = $contentNode->nodeValue;
        if ( $contentNode->hasAttribute( 'encoded' ) &&
             $contentNode->getAttribute( 'encoded' ) )
        {
            $encoded = true;
        }

        if ( $encoded )
        {
            $patchContent = base64_decode( $patchContent );
        }

        return $patchContent;
    }

    /*!
     \static
     Get script content from DOM Node

     \param Patch Element dom node

     \return patch content.
    */
    static function patchScriptContent( $patchNode )
    {
        $patchContent = '';

        $contentNode = $patchNode->getElementsByTagName( 'Script' )->item( 0 );
        $patchContent = $contentNode->nodeValue;

        // Always encoded
        $patchContent = base64_decode( $patchContent );

        return $patchContent;
    }
    /*!
     DEPRECATED
     It doesn't make sense to check from_release_tag anymore
     due to a bug: we cannot add a patch based on 4.2.3 release for newly created branch.
     Now the base release can be one of all release strings like 1.1.1 and so on.

     Check if current patch is base release

     \return true if base release, false if not
    */
    function isBaseRelease()
    {
        return true;
    }

    /*!
     \static
     Check if release tag is valid.

     \param release tag
     \param minNumDepth,  ex: 2 -> 3.4 , 4 -> 3.5.2.5

     \return True if release tag is valid.
    */
    static function validReleaseTag( $releaseTag, $minNumDepth = 3 )
    {
        $releaseTag = trim( $releaseTag );
        $matches = array();

        $matchExpr = "/" . substr( str_repeat( "[0-9]+\.", $minNumDepth ), 0, -2 ) . "(.*)" . "/";

        preg_match( $matchExpr, $releaseTag, $matches );

        return ( count( $matches ) >= 1 &&
                 $matches[0] === $releaseTag );
    }

    /*!
     Set option

     \param option name
     \param option value
    */
    function setOption( $attr, $value )
    {
        $optionArray = $this->attribute( 'option_array' );
        $optionArray[$attr] = $value;
        $this->setAttribute( 'options', serialize( $optionArray ) );
    }

    /*!
     Check if option is set.

     \param option name
    */
    function hasOption( $attr )
    {
        $optionArray = $this->attribute( 'option_array' );
        return isset( $optionArray[$attr] );
    }

    /*
     Get option

     \param option name

     \return option value
    */
    function option( $attr )
    {
        $optionArray = $this->attribute( 'option_array' );
        return isset( $optionArray[$attr] ) ? $optionArray[$attr] : false;
    }

    /*!
     \reimp
    */
    function setAttribute( $attr, $value )
    {
        switch( $attr )
        {
            case 'status':
            {
                $existingUserInfoString = $this->attribute( 'status_info' );
                $existingUserInfo = !$existingUserInfoString ? array() : unserialize( $existingUserInfoString );

                if ( isset( $existingUserInfo[(string)$value] ) )
                {
                    $existingUserInfo[(string)$value]['modifier_id'] = eZUser::currentUserID();
                    $existingUserInfo[(string)$value]['modified'] = time();
                }
                else
                {
                    $existingUserInfo[(string)$value] = array();
                    $existingUserInfo[(string)$value]['creator_id'] = eZUser::currentUserID();
                    $existingUserInfo[(string)$value]['created'] = time();
                }

                $this->setAttribute( 'status_info', serialize( $existingUserInfo ) );
                eZNetLargeObject::setAttribute( $attr, $value );
            } break;

            default:
            {
                eZNetLargeObject::setAttribute( $attr, $value );
            } break;
        }
    }

    /*!
     \static
     \deprecated fetchListCount is a more generic function for used for counting patches

     Get eZNetPatch count

     \param version status ( optional )
     \param patch status ( optional )
     \param name of subclass
    */
    static function countByStatusAndClass( $versionStatus = eZNetPatchBase::VersionStatusPublished,
                           $patchStatus = array( array( eZNetPatchBase::StatusAlpha,
                                                        eZNetPatchBase::StatusBeta,
                                                        eZNetPatchBase::StatusRC,
                                                        eZNetPatchBase::StatusFinal,
                                                        eZNetPatchBase::StatusSecurity ) ),
                           $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        $resultSet = call_user_func_array( array( $className, 'fetchObjectList' ),
                                           array( call_user_func_array( array( $className, 'definition' ), array() ),
                                                  array(),
                                                  array( 'status' => $patchStatus,
                                                         'version_status' => $versionStatus ),
                                                  null,
                                                  null,
                                                  false,
                                                  false,
                                                  array( array( 'operation' => 'count(id)',
                                                                'name' => 'count' ) ) ) );
        return $resultSet[0]['count'];

    }

    /*!
     Fetch next patch ( according to required patch id )

     \param $asObject

     \return eZNetPatchBase object, null if not exists.
    */
    function nextPatch( $asObject = true )
    {
        $className = get_class( $this );
        return call_user_func_array( array( $className, 'fetchObject' ),
                                     array( call_user_func_array( array( $className, 'definition' ),
                                                                  array() ),
                                            null,
                                            array( 'required_patch_id' => $this->attribute( 'id' ) ),
                                            $asObject ) );
    }

    /*!
     \static

     Fetch draft. If no draft exist, create draft from existing published object
    */
    static function fetchDraftByClass( $id,
                                $force = true,
                                $asObject = true,
                                $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        $draft = call_user_func_array( array( $className, 'fetch' ),
                                       array( $id,
                                              eZNetPatchBase::VersionStatusDraft,
                                              $asObject ) );
        if ( !$draft &&
             $force )
        {
            $draft = call_user_func_array( array( $className, 'fetch' ),
                                           array( $id,
                                                  eZNetPatchBase::VersionStatusPublished,
                                                  $asObject ) );

            if ( $draft )
            {
                $draft->setAttribute( 'version_status', eZNetPatchBase::VersionStatusDraft );
                $draft->sync();
            }
        }

        return $draft;
    }

    /*!
     Publish current object
    */
    function publish()
    {
        $this->setAttribute( 'version_status', eZNetPatchBase::VersionStatusPublished );
        $this->setAttribute( 'modified', time() );
        $this->store();
        $this->removeDraft();
    }

    /*!
     Remove draft.
    */
    function removeDraft()
    {
        $className = get_class( $this );

        $draft = call_user_func_array( array( $className, 'fetchDraft' ),
                                       array( $this->attribute( 'id' ),
                                              false ) );
        if ( $draft )
        {
            $draft->remove();
        }
    }

    /*!
     \static

     Create new patch item
    */
    static function createByClass( $branchID, $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        $patch = new $className( array( 'version_status' => eZNetPatchBase::VersionStatusDraft,
                                        call_user_func_array( array( $className, 'branchIDField' ), array() ) => $branchID,
                                        'created' => time(),
                                        'creator_id' => eZUser::currentUserID() ) );
        $patch->setAttribute( 'status', eZNetPatchBase::StatusAlpha );

        return $patch;
    }

    /*!
     Check if required patch is OK

     \param required patch ID

     \return error message if required patch ID is invalid. False if everything is OK.
    */
    function checkRequiredPatchID( $patchID )
    {
        $className = get_class( $this );

        if ( $patchID == eZNetPatchBase::RequiredNone  )
        {
            if ( $this->isBaseRelease() )
            {
                return false;
            }
            else
            {
                return "This patch is not a base release";
            }
        }
        $requiredPatch = call_user_func_array( array( $className, 'fetch' ),
                                               array( $patchID ) );
        // Check if required patch exists
        if ( !$requiredPatch )
        {
            return "Patch not found.";
        }

        // Check if required patch has required patch set ( required ).
        if ( !$requiredPatch->attribute( 'required_patch_id' ) )
        {
            return "Required patch does not have a required patch selected.";
        }

        // Check if required patch is in the same branch
        $branchIDField = call_user_func_array( array( $className, 'branchIDField' ), array() );
        if ( $this->attribute( $branchIDField ) != $requiredPatch->attribute( $branchIDField ) )
        {
            return "Required patch is of different branch.";
        }

        // Check if required patch is required by other patches as well ( not allowed ).
        $requiredByOther = false;
        foreach( call_user_func_array( array( $className, 'fetchListByRequiredPatchID' ),
                                       array( $patchID ) ) as $patch )
        {
            if ( $patch->attribute( 'id' ) != $this->attribute( 'id' ) )
            {
                $requiredByOther = true;
                break;
            }
        }
        if ( $requiredByOther )
        {
            return "Patch is required by other patch. This is not allowed.";
        }

        return false;
    }

    /*!
     \static

     Fetch list branch id

     \param branch ID ( can also be list, example : array( array( 1, 2, 3 ) )
     \param patch status
     \param version status
     \param $asObject
     \param additional condition array ( optional )
     \param name of subclass
    */
    static function fetchListByBranchIDAndClass( $branchID,
                                         $status = array( array( eZNetPatchBase::StatusFinal,
                                                                 eZNetPatchBase::StatusSecurity ) ),
                                         $versionStatus = eZNetPatchBase::VersionStatusPublished,
                                         $asObject = true,
                                         $extraConditions = array(),
                                         $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        $conditionList = array_merge( $extraConditions,
                                      array( call_user_func_array( array( $className, 'branchIDField' ), array() ) => $branchID,
                                             'status' => $status,
                                             'version_status' => $versionStatus ) );

        return call_user_func_array( array( $className, 'fetchObjectList' ),
                                     array( call_user_func_array( array( $className, 'definition' ), array() ),
                                            null,
                                            $conditionList,
                                            null,
                                            null,
                                            $asObject ) );
    }

    /*!
     \static

     Fetch list by required patch id

     \param required patch ID
     \param patch status
     \param version status
     \param $asObject
     \param name of subclass
    */
    static function fetchListByRequiredPatchIDAndClass( $requiredPatchID,
                                                $status = array( array( eZNetPatchBase::StatusFinal,
                                                                        eZNetPatchBase::StatusSecurity ) ),
                                                $versionStatus = eZNetPatchBase::VersionStatusPublished,
                                                $asObject = true,
                                                $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        return call_user_func_array( array( $className, 'fetchObjectList' ),
                                     array( call_user_func_array( array( $className, 'definition' ), array() ),
                                            null,
                                            array( 'required_patch_id' => $requiredPatchID,
                                                   'status' => $status,
                                                   'version_status' => $versionStatus ),
                                            null,
                                            null,
                                            $asObject ) );
    }

    /*!
     \static

     Fetch list of Network patches.
    */
    static function fetchListByClass( $offset = 0,
                                      $limit = 10,
                                      $status = array( array( eZNetPatchBase::StatusFinal,
                                                              eZNetPatchBase::StatusSecurity ) ),
                                      $asObject = true,
                                      $className = false,
                                      array $sort = null )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        return call_user_func_array( array( $className, 'fetchObjectList' ),
                                     array( call_user_func_array( array( $className, 'definition' ), array() ),
                                            null,
                                            $status,
                                            $sort,
                                            array( 'limit' => $limit,
                                                   'offset' => $offset ),
                                            $asObject,
                                            ) );
    }

    /*!
     \static
     Get eZNetPatch count

     \param eZPersistenObject conds
    */
    static function fetchListCountByClass( $conds = array(), $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        $resultSet = call_user_func_array( array( $className, 'fetchObjectList' ),
                                           array( call_user_func_array( array( $className, 'definition' ), array() ),
                                                  array(),
                                                  $conds,
                                                  null,
                                                  null,
                                                  false,
                                                  false,
                                                  array( array( 'operation' => 'count(id)',
                                                                'name' => 'count' ) )
                                                  ) );
        return $resultSet[0]['count'];
    }


    /*!
     \reimp
    */
    static function fetchByClass( $id, $version = eZNetPatchBase::VersionStatusPublished, $asObject = true, $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        return call_user_func_array( array( $className, 'fetchObject' ),
                                     array( call_user_func_array( array( $className, 'definition' ), array() ),
                                            null,
                                            array( 'id' => $id,
                                                   'version_status' => $version ),
                                            $asObject ) );
    }

    /*!
     \static

     Get status name map
    */
    static function statusNameMap()
    {
        return array( eZNetPatchBase::StatusAlpha => ezi18n( 'ez_network', 'Alpha' ),
                      eZNetPatchBase::StatusBeta => ezi18n( 'ez_network', 'Beta' ),
                      eZNetPatchBase::StatusRC => ezi18n( 'ez_network', 'RC' ),
                      eZNetPatchBase::StatusFinal => ezi18n( 'ez_network', 'Final' ),
                      eZNetPatchBase::StatusRemoved => ezi18n( 'ez_network', 'Removed' ),
                      eZNetPatchBase::StatusSecurity => ezi18n( 'ez_network', 'Security patch' ) );
    }

    /*!
     \static

     Status info map
    */
    static function statusInfoMap()
    {
        return array( eZNetPatchBase::StatusAlpha => 'alpha_info',
                      eZNetPatchBase::StatusBeta => 'beta_info',
                      eZNetPatchBase::StatusRC => 'rc_info',
                      eZNetPatchBase::StatusFinal => 'final_info' );
    }

    /*!
     \static

     Check if patch with given ID exists.

     \param patch ID
     \param patch status
     \param name of subclass

     \return True if patch exists, false if not.
    */
    static function existsByClass( $patchID, $versionStatus = eZNetPatchBase::VersionStatusPublished, $className = false )
    {
        if ( !$className )
        {
            $className = eZNetUtils::callbackClassName();
        }

        $resultSet = call_user_func_array( array( $className, 'fetchObjectList' ),
                                           array( call_user_func_array( array( $className, 'definition' ), array() ),
                                                  array(),
                                                  array( 'id' => $patchID,
                                                         'version_status' => $versionStatus ),
                                                  null,
                                                  null,
                                                  false,
                                                  false,
                                                  array( array( 'operation' => '1' ) ) ) );
        return count( $resultSet ) == 1;
    }

    /*!
     \static
     \abstract
    */
    static function branchIDField()
    {
        return false;
    }
}

?>
