<?php
//
// Created on: <06-Nov-2002 15:10:02 wy>
// Forked on: <20-Des-2007 13:02:06 ar> from eZDHTMLInput class
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
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


/*! \file ezoexmlinput.php
*/

/*!
  \class eZOEXMLInput
  \brief The class eZOEXMLInput does

*/
class eZOEXMLInput extends eZXMLInputHandler
{
     /**
     * Constructor
     * For more info see {@link eZXMLInputHandler::eZXMLInputHandler()}
     *
     * @param string $xmlData
     * @param string $aliasedType
     * @param eZContentObjectAttribute $contentObjectAttribute
     */
    function eZOEXMLInput( &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        $this->eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute );

        $contentIni = eZINI::instance( 'content.ini' );
        if ( $contentIni->hasVariable( 'header', 'UseStrictHeaderRule' ) === true )
        {
            if ( $contentIni->variable( 'header', 'UseStrictHeaderRule' ) === 'true' )
                $this->IsStrictHeader = true;
        }

        $this->eZPublishVersion = eZPublishSDK::majorVersion() + eZPublishSDK::minorVersion() * 0.1;

        $ezxmlIni = eZINI::instance( 'ezxml.ini' );
        if ( $ezxmlIni->hasVariable( 'InputSettings', 'AllowMultipleSpaces' ) === true )
        {
            $allowMultipleSpaces = $ezxmlIni->variable( 'InputSettings', 'AllowMultipleSpaces' );
            $this->allowMultipleSpaces = $allowMultipleSpaces === 'true' ? true : false;
        }
        if ( $ezxmlIni->hasVariable( 'InputSettings', 'AllowNumericEntities' ) )
        {
            $allowNumericEntities = $ezxmlIni->variable( 'InputSettings', 'AllowNumericEntities' );
            $this->allowNumericEntities = $allowNumericEntities === 'true' ? true : false;
        }
    }

     /**
     * $nativeCustomTags
     * List of custom tags that have a native xhtml counterpart.
     * {@link eZOEInputParser::tagNameCustomHelper()} hendels
     * input parsing.
     *
     * @static
     */
    public static $nativeCustomTags = array(
                  'sup' => 'sup',
                  'sub' => 'sub'
                  );

    /**
     * List of template callable attributes
     *
     * @return array
     */
    function attributes()
    {
        return array_merge( array(
                      'is_editor_enabled',
                      'can_disable',
                      'editor_layout_settings',
                      'browser_supports_dhtml_type',
                      'is_compatible_version',
                      'version',
                      'ezpublish_version',
                      'xml_tag_alias',
                      'json_xml_tag_alias' ),
                      parent::attributes() );
    }

    /**
     * Function used by template system to call ezoe functions
     *
     * @param string $name
     * @return mixed
     */
    function attribute( $name )
    {
        if ( $name === 'is_editor_enabled' )
            $attr = self::isEditorEnabled();
        else if ( $name === 'can_disable' )
            $attr = $this->currentUserHasAccess( 'disable_editor' );
        else if ( $name === 'editor_layout_settings' )
            $attr = $this->getEditorLayoutSettings();
        else if ( $name === 'browser_supports_dhtml_type' )
            $attr = self::browserSupportsDHTMLType();
        else if ( $name === 'is_compatible_version' )
            $attr = $this->isCompatibleVersion();
        else if ( $name === 'version' )
            $attr = self::version();
        else if ( $name === 'ezpublish_version' )
            $attr = $this->eZPublishVersion;
        else if ( $name === 'xml_tag_alias' )
            $attr =  self::getXmlTagAliasList();
        else if ( $name === 'json_xml_tag_alias' )
            $attr =  json_encode( self::getXmlTagAliasList() );
        else
            $attr = parent::attribute( $name );
        return $attr;
    }

     /**
     * browserSupportsDHTMLType
     * Identify supported browser by layout engine using user agent string.
     *
     * @static
     * @return string|false Name of supported layout engine or false
     */
    public static function browserSupportsDHTMLType()
    {
        if ( self::$browserType === null )
        {
            self::$browserType = false;
            $userAgent = eZSys::serverVariable( 'HTTP_USER_AGENT' );
            if ( strpos( $userAgent, 'Presto' ) !== false &&
                 preg_match('/Presto\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 2.1 )
                    self::$browserType = 'Presto';
            }
            else if ( strpos( $userAgent, 'Opera' ) !== false &&
                      preg_match('/Opera\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                // Presto is not part of the user agent string on Opera < 9.6
                if ( $browserInfo[1] >= 9.5 )
                    self::$browserType = 'Presto';
            }
            else if ( strpos( $userAgent, 'Trident' ) !== false &&
                      preg_match('/Trident\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 4.0 )
                    self::$browserType = 'Trident';
            }
            else if ( strpos( $userAgent, 'MSIE' ) !== false &&
                      preg_match('/MSIE[ \/]([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                // IE didn't have Trident in it's user agent string untill IE 8.0
                if ( $browserInfo[1] >= 6.0 )
                    self::$browserType = 'Trident';
            }
            else if ( strpos( $userAgent, 'Gecko' ) !== false &&
                      preg_match('/rv:([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 1.8 )
                    self::$browserType = 'Gecko';
            }
            else if ( strpos( $userAgent, 'WebKit' ) !== false &&
                      strpos( $userAgent, 'Mobile' ) === false && // Mobile webkit does not have rich text editing support
                      strpos( $userAgent, 'Android' ) === false &&
                      strpos( $userAgent, 'iPad' ) === false &&
                      strpos( $userAgent, 'iPhone' ) === false &&
                      strpos( $userAgent, 'iPod' ) === false &&
                      preg_match('/WebKit\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 522.0 )
                    self::$browserType = 'WebKit';
            }
            if ( self::$browserType === false )
                eZDebug::writeNotice( 'Browser not supported: ' . $userAgent, __METHOD__ );
        }
        return self::$browserType;
    }

     /**
     * getXmlTagAliasList
     * Get and chache XmlTagNameAlias from ezoe.ini
     *
     * @static
     * @return array
     */
    public static function getXmlTagAliasList()
    {
        if ( self::$xmlTagAliasList === null )
        {
            $ezoeIni = eZINI::instance( 'ezoe.ini' );
            self::$xmlTagAliasList = $ezoeIni->variable( 'EditorSettings', 'XmlTagNameAlias' );
        }
        return self::$xmlTagAliasList;
    }

     /**
     * isCompatibleVersion
     *
     * @return bool Return true if current eZ Publish verion is supported.
     */
    function isCompatibleVersion()
    {
        return $this->eZPublishVersion >= 4.0;
    }

     /**
     * version
     *
     * @static
     * @return string ezoe verion number
     */
    public static function version()
    {
        $info = ezoeInfo::info();
        $version = $info['version'];
        return $version;
    }

     /**
     * isValid
     * Called by handler loading code to see if this is a valid handler.
     *
     * @return bool
     */
    function isValid()
    {
        if ( !$this->currentUserHasAccess() )
        {
            eZDebug::writeNotice('Current user does not have access to ezoe, falling back to normal xml editor!', __METHOD__ );
            return false;
        }

        if ( !self::browserSupportsDHTMLType() )
        {
            if ( $this->currentUserHasAccess( 'disable_editor' ) )
            {
                eZDebug::writeNotice('Current browser is not supported by ezoe, falling back to normal xml editor!', __METHOD__ );
                return false;
            }
            eZDebug::writeWarning('Current browser is not supported by ezoe, but user does not have access to disable editor!', __METHOD__ );
        }

        return true;
    }

     /**
     * customObjectAttributeHTTPAction
     * Custom http actions exposed by the editor.
     *
     * @param  eZHTTPTool $http
     * @param  string $action
     * @param  eZContentObjectAttribute $contentObjectAttribute
     */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute )
    {
        switch ( $action )
        {
            case 'enable_editor':
            {
                self::setIsEditorEnabled( true );
            } break;
            case 'disable_editor':
            {
                if ( $this->currentUserHasAccess( 'disable_editor' ) )
                    self::setIsEditorEnabled( false );
                else
                    eZDebug::writeError( 'Current user does not have access to disable editor, but trying anyway!', __METHOD__ );
            } break;
            default :
            {
                eZDebug::writeError( 'Unknown custom HTTP action: ' . $action, __METHOD__ );
            } break;
        }
    }

     /**
     * editTemplateSuffix
     *
     * @param  eZContentObjectAttribute $contentObjectAttribute
     * @return string 'ezoe'
     */
    function editTemplateSuffix( &$contentObjectAttribute )
    {
        return 'ezoe';
    }

     /**
     * isEditorEnabled
     *
     * @static
     * @return bool true if editor is enabled
     */
    public static function isEditorEnabled()
    {
        $dhtmlInput = true;
        $http = eZHTTPTool::instance();
        if ( $http->hasSessionVariable( 'eZOEXMLInputExtension' ) )
            $dhtmlInput = $http->sessionVariable( 'eZOEXMLInputExtension' );
        return $dhtmlInput;
    }

     /**
     * setIsEditorEnabled
     *
     * @static
     * @param bool $isEnabled sets editor to enabled / disabled
     */
    public static function setIsEditorEnabled( $isEnabled )
    {
        $http = eZHTTPTool::instance();
        $http->setSessionVariable( 'eZOEXMLInputExtension', $isEnabled );
    }

     /**
     * currentUserHasAccess
     *
     * @param string $view name of ezoe view to check for access on
     * @return bool
     */
    function currentUserHasAccess( $view = 'editor' )
    {
        if ( !isset( self::$userAccessHash[ $view ] ) )
        {
            self::$userAccessHash[ $view ] = false;
            $user = eZUser::currentUser();
            if ( $user instanceOf eZUser )
            {
                $result = $user->hasAccessTo( 'ezoe', $view );
                if ( $result['accessWord'] === 'yes'  )
                {
                    self::$userAccessHash[ $view ] = true;
                }
                else if ( $result['accessWord'] === 'limited' )
                {
                     foreach ( $result['policies'] as $pkey => $limitationArray )
                     {
                        foreach ( $limitationArray as $key => $valueList  )
                        {
                            switch( $key )
                            {
                                case 'User_Section':
                                {
                                    if ( in_array( $this->ContentObjectAttribute->attribute('object')->attribute( 'section_id' ), $valueList ) )
                                    {
                                        self::$userAccessHash[ $view ] = true;
                                        break 3;
                                    }
                                } break;
                                case 'User_Subtree':
                                {
                                    $node = $this->ContentObjectAttribute->attribute('object')->attribute('main_node');
                                    if ( !$node instanceof eZContentObjectTreeNode )
                                    {
                                        // get temp parent node if object don't have node assignmet yet
                                        $tempParentNodeId = $this->ContentObjectAttribute->attribute('object_version')->attribute('main_parent_node_id');
                                        $node = eZContentObjectTreeNode::fetch( $tempParentNodeId );
                                    }
                                    $path = $node->attribute( 'path_string' );
                                    foreach ( $valueList as $subtreeString )
                                    {
                                        if ( strstr( $path, $subtreeString ) )
                                        {
                                            self::$userAccessHash[ $view ] = true;
                                            break 4;
                                        }
                                    }
                                } break;
                            }
                        }
                     }
                }
            }
        }
        return self::$userAccessHash[ $view ];
    }

     /**
     * getEditorGlobalLayoutSettings
     * used by {@link eZOEXMLInput::getEditorLayoutSettings()}
     *
     * @static
     * @return array hash with global layout settings for the editor
     */
    public static function getEditorGlobalLayoutSettings()
    {
        if ( self::$editorGlobalLayoutSettings === null )
        {
            $oeini = eZINI::instance( 'ezoe.ini' );
            self::$editorGlobalLayoutSettings = array(
                'buttons' => $oeini->variable('EditorLayout', 'Buttons' ),
                'toolbar_location' => $oeini->variable('EditorLayout', 'ToolbarLocation' ),
                'path_location' => $oeini->variable('EditorLayout', 'PathLocation' ),
            );
        }
        return self::$editorGlobalLayoutSettings;
    }

     /**
     * getEditorLayoutSettings
     * generate current layout settings depending on ini settings, current
     * class attribute settings and current user access.
     *
     * @return array hash with layout settings for the editor
     */
    function getEditorLayoutSettings()
    {
        if ( $this->editorLayoutSettings === null )
        {
            $oeini = eZINI::instance( 'ezoe.ini' );
            $xmlini = eZINI::instance( 'ezxml.ini' );

            // get global layout settings
            $editorLayoutSettings = self::getEditorGlobalLayoutSettings();

            // get custom layout features, works only in eZ Publish 4.1 and higher
            $contentClassAttribute = $this->ContentObjectAttribute->attribute('contentclass_attribute');
            $buttonPreset = $contentClassAttribute->attribute('data_text2');
            $buttonPresets = $xmlini->hasVariable( 'TagSettings', 'TagPresets' ) ? $xmlini->variable( 'TagSettings', 'TagPresets' ) : array();

            if( $buttonPreset && isset( $buttonPresets[$buttonPreset] ) )
            {
                if ( $oeini->hasSection( 'EditorLayout_' . $buttonPreset ) )
                {
                    if ( $oeini->hasVariable( 'EditorLayout_' . $buttonPreset , 'Buttons' ) )
                        $editorLayoutSettings['buttons'] = $oeini->variable( 'EditorLayout_' . $buttonPreset , 'Buttons' );

                    if ( $oeini->hasVariable( 'EditorLayout_' . $buttonPreset , 'ToolbarLocation' ) )
                        $editorLayoutSettings['toolbar_location'] = $oeini->variable( 'EditorLayout_' . $buttonPreset , 'ToolbarLocation' );

                    if ( $oeini->hasVariable( 'EditorLayout_' . $buttonPreset , 'PathLocation' ) )
                        $editorLayoutSettings['path_location'] = $oeini->variable( 'EditorLayout_' . $buttonPreset , 'PathLocation' );
                }
                else
                {
                    eZDebug::writeWarning( 'Undefined EditorLayout : EditorLayout_' . $buttonPreset, __METHOD__ );
                }
            }

            $contentini = eZINI::instance( 'content.ini' );
            $tags = $contentini->variable('CustomTagSettings', 'AvailableCustomTags' );
            $hideButtons = array();
            $showButtons = array();

            // filter out custom tag icons if the custom tag is not enabled
            if ( !in_array('underline', $tags ) )
                $hideButtons[] = 'underline';

            if ( !in_array('sub', $tags ) )
                $hideButtons[] = 'sub';

            if ( !in_array('sup', $tags ) )
                $hideButtons[] = 'sup';

            if ( !in_array('pagebreak', $tags ) )
                $hideButtons[] = 'pagebreak';

            // filter out relations buttons if user dosn't have access to relations
            if ( !$this->currentUserHasAccess( 'relations' ) )
            {
                $hideButtons[] = 'image';
                $hideButtons[] = 'object';
                $hideButtons[] = 'file';
                $hideButtons[] = 'media';
            }

            // filter out align buttons on eZ Publish 4.0.x
            if ( $this->eZPublishVersion < 4.1 )
            {
                $hideButtons[] = 'justifyleft';
                $hideButtons[] = 'justifycenter';
                $hideButtons[] = 'justifyright';
                $hideButtons[] = 'justifyfull';
            }

            foreach( $editorLayoutSettings['buttons'] as $buttonString )
            {
                if ( strpos( $buttonString, ',' ) !== false )
                {
                    foreach( explode( ',', $buttonString ) as $button )
                    {
                        if ( !in_array( $button, $hideButtons ) )
                            $showButtons[] = trim( $button );
                    }
                }
                else if ( !in_array( $buttonString, $hideButtons ) )
                    $showButtons[] = trim( $buttonString );
            }

            $editorLayoutSettings['buttons'] = $showButtons;
            $this->editorLayoutSettings = $editorLayoutSettings;
        }
        return $this->editorLayoutSettings;
    }

     /**
     * updateUrlObjectLinks
     * Updates URL to object links.
     *
     * @static
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param array $urlIDArray
     */
    public static function updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray )
    {
        $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $objectAttributeVersion = $contentObjectAttribute->attribute('version');

        foreach( $urlIDArray as $urlID )
        {
            $linkObjectLink = eZURLObjectLink::fetch( $urlID, $objectAttributeID, $objectAttributeVersion );
            if ( $linkObjectLink == null )
            {
                $linkObjectLink = eZURLObjectLink::create( $urlID, $objectAttributeID, $objectAttributeVersion );
                $linkObjectLink->store();
            }
        }
    }

     /**
     * validateInput
     * Validates and parses input using {@link eZOEInputParser::process}
     * and saves data if valid.
     *
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return int signals if status is valid or not
     */
    function validateInput( $http, $base, $contentObjectAttribute )
    {
        if ( !$this->isEditorEnabled() )
        {
            $aliasedHandler = $this->attribute( 'aliased_handler' );
            return $aliasedHandler->validateInput( $http, $base, $contentObjectAttribute );
        }
        if ( $http->hasPostVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $text = $http->postVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            if ( self::browserSupportsDHTMLType() === 'Trident' ) // IE
            {
                $text = str_replace( "\t", '', $text);
            }

            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext-ezoe',
                                        $text,
                                        __METHOD__ . ' html from client' );

            $parser = new eZOEInputParser();
            $document = $parser->process( $text );

            // Remove last empty paragraph (added in the output part)
            $parent = $document->documentElement;
            $lastChild = $parent->lastChild;
            while( $lastChild && $lastChild->nodeName !== 'paragraph' )
            {
                $parent = $lastChild;
                $lastChild = $parent->lastChild;
            }

            if ( $lastChild && $lastChild->nodeName === 'paragraph' )
            {
                $textChild = $lastChild->lastChild;
                // $textChild->textContent == " " : string(2) whitespace in Opera
                if ( !$textChild ||
                     ( $lastChild->childNodes->length == 1 &&
                       $textChild->nodeType == XML_TEXT_NODE &&
                       ( $textChild->textContent == " " || $textChild->textContent == ' ' || $textChild->textContent == '' || $textChild->textContent == '&nbsp;' ) ) )
                {
                    $parent->removeChild( $lastChild );
                }
            }

            $oeini = eZINI::instance( 'ezoe.ini' );
            $validationParameters = $contentObjectAttribute->validationParameters();
            if ( !( isset( $validationParameters['skip-isRequired'] ) && $validationParameters['skip-isRequired'] === true )
              && $parser->getDeletedEmbedIDArray( $oeini->variable('EditorSettings', 'ValidateEmbedObjects' ) === 'enabled' ) )
            {
                self::$showEmbedValidationErrors = true;
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'design/standard/ezoe/handler',
                                         'Some objects used in embed(-inline) tags have been deleted and are no longer available.' ) );
                return eZInputValidator::STATE_INVALID;
            }

            if ( $contentObjectAttribute->validateIsRequired() )
            {
                $root = $document->documentElement;
                if ( $root->childNodes->length == 0 )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'Content required' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }

            // Update URL-object links
            $urlIDArray = $parser->getUrlIDArray();
            if ( !empty( $urlIDArray ) )
            {
                self::updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray );
            }

            $contentObject = $contentObjectAttribute->attribute( 'object' );
            $contentObject->appendInputRelationList( $parser->getEmbeddedObjectIDArray(),
                                                     eZContentObject::RELATION_EMBED );
            $contentObject->appendInputRelationList( $parser->getLinkedObjectIDArray(),
                                                     eZContentObject::RELATION_LINK );

            $xmlString = eZXMLTextType::domString( $document );

            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext-ezoe',
                                        $xmlString,
                                        __METHOD__ . ' generated xml' );

            $contentObjectAttribute->setAttribute( 'data_text', $xmlString );
            $contentObjectAttribute->setValidationLog( $parser->Messages );

            return eZInputValidator::STATE_ACCEPTED;
        }
        else
        {
            return eZInputValidator::STATE_ACCEPTED;
        }
    }


    /*
      Editor inner output implementation
      As in code that does all the xml to xhtml transformation (for use inside the editor)
    */

    // Get section level and reset curent xml node according to input header.
    function &sectionLevel( &$sectionLevel, $headerLevel, &$TagStack, &$currentNode, &$domDocument )
    {
        if ( $sectionLevel < $headerLevel )
        {
            if ( $this->IsStrictHeader )
            {
                $sectionLevel += 1;
            }
            else
            {
                if ( ( $sectionLevel + 1 ) == $headerLevel )
                {
                    $sectionLevel += 1;
                }
                else
                {
                    for ( $i = 1; $i <= ( $headerLevel - $sectionLevel - 1 ); $i++ )
                    {
                        // Add section tag
                        unset( $subNode );
                        $subNode = new DOMElemenetNode( 'section' );
                        $currentNode->appendChild( $subNode );
                        $childTag = $this->SectionArray;
                        $TagStack[] = array( 'TagName' => 'section',
                                             'ParentNodeObject' => &$currentNode,
                                             'ChildTag' => $childTag );
                        $currentNode = $subNode;
                    }
                    $sectionLevel = $headerLevel;
                }
            }
        }
        elseif ( $sectionLevel == $headerLevel )
        {
            $lastNodeArray = array_pop( $TagStack );
            $lastNode = $lastNodeArray['ParentNodeObject'];
            $currentNode = $lastNode;
            $sectionLevel = $headerLevel;
        }
        else
        {
            for ( $i = 1; $i <= ( $sectionLevel - $headerLevel + 1 ); $i++ )
            {
                $lastNodeArray = array_pop( $TagStack );
                $lastTag = $lastNodeArray['TagName'];
                $lastNode = $lastNodeArray['ParentNodeObject'];
                $lastChildTag = $lastNodeArray['ChildTag'];
                $currentNode = $lastNode;
            }
            $sectionLevel = $headerLevel;
        }
        return $currentNode;
    }

    /*!
     Returns the input XML representation of the datatype.
    */
    function inputXML( )
    {
        $node = null;
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = false;
        if ( $this->XMLData )
        {
            $success = $dom->loadXML( $this->XMLData );
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext',
                                    $this->XMLData,
                                    __METHOD__ . ' xml string stored in database' );

        $output = '';
        $browserEngineName = self::browserSupportsDHTMLType();

        if ( $success )
        {
            $rootSectionNode = $dom->documentElement;
            $output .= $this->inputSectionXML( $rootSectionNode, 0 );
        }

        if ( $browserEngineName === 'Trident' )
        {
            $output = str_replace( '<p></p>', '<p>&nbsp;</p>', $output );
        }
        else /* if ( $browserEngineName !== 'Presto' ) */
        {
            $output = str_replace( '<p></p>', '<p><br /></p>', $output );
        }

        $output = str_replace( "\n", '', $output );

        if ( $output )
        {
            if ( $browserEngineName === 'Trident' )
            {
                $output .= '<p>&nbsp;</p>';
            }
            else
            {
                $output .= '<p><br /></p>';
            }
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext',
                                    $output,
                                    __METHOD__ . ' xml output to return' );

        $output = htmlspecialchars( $output, ENT_COMPAT, 'UTF-8' );

        return $output;
    }

    /*!
     \private
     \return the user input format for the given section
    */
    function &inputSectionXML( &$section, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = '';

        foreach ( $section->childNodes as $sectionNode )
        {
            if ( $tdSectionLevel == null )
            {
                $sectionLevel = $currentSectionLevel;
            }
            else
            {
                $sectionLevel = $tdSectionLevel;
            }

            $tagName = $sectionNode instanceof DOMNode ? $sectionNode->nodeName : '';

            switch ( $tagName )
            {
                case 'header' :
                {
                    $headerClassName = $sectionNode->getAttribute( 'class' );
                    $headerAlign = $sectionNode->getAttribute( 'align' );
                    $customAttributePart = self::getCustomAttrPart( $sectionNode, $styleString );

                    if ( $headerClassName )
                    {
                        $customAttributePart .= ' class="' . $headerClassName . '"';
                    }

                    if ( $headerAlign )
                    {
                        $customAttributePart .= ' align="' . $headerAlign . '"';
                    }

                    $tagContent = '';
                    // render children tags
                    $tagChildren = $sectionNode->childNodes;
                    foreach ( $tagChildren as $childTag )
                    {
                        $tagContent .= $this->inputTagXML( $childTag, $currentSectionLevel, $tdSectionLevel );
                        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext',
                                                    $tagContent,
                                                    __METHOD__ . ' tag content of <header>' );
                    }

                    switch ( $sectionLevel )
                    {
                        case '2':
                        case '3':
                        case '4':
                        case '5':
                        case '6':
                        {
                            $level = $sectionLevel;
                        }break;
                        default:
                        {
                            $level = 1;
                        }break;
                    }
                    $archorName = $sectionNode->getAttribute( 'anchor_name' );
                    if ( $archorName != null )
                    {
                        $output .= "<h$level$customAttributePart$styleString><a name=\"$archorName\"" .
                                   ' class="mceItemAnchor"></a>' . $sectionNode->textContent. "</h$level>";
                    }
                    else
                    {
                        $output .= "<h$level$customAttributePart$styleString>" . $tagContent . "</h$level>";
                    }

                }break;

                case 'paragraph' :
                {
                    if ( $tdSectionLevel == null )
                    {
                        $output .= $this->inputParagraphXML( $sectionNode, $currentSectionLevel );
                    }
                    else
                    {
                        $output .= $this->inputParagraphXML( $sectionNode,
                                                             $currentSectionLevel,
                                                             $tdSectionLevel );
                    }
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    if ( $tdSectionLevel == null )
                    {
                        $output .= $this->inputSectionXML( $sectionNode, $sectionLevel );
                    }
                    else
                    {
                        $output .= $this->inputSectionXML( $sectionNode,
                                                           $currentSectionLevel,
                                                           $sectionLevel );
                    }
                }break;

                case '#text' :
                {
                    //ignore whitespace
                }break;

                default :
                {
                    eZDebug::writeError( "Unsupported tag at this level: $tagName", __METHOD__ );
                }break;
            }
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given list item
    */
    function &inputListXML( &$listNode, $currentSectionLevel, $listSectionLevel = null, $noParagraphs = true )
    {
        $output = '';
        $tagName = $listNode instanceof DOMNode ? $listNode->nodeName : '';

        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= $this->inputParagraphXML( $listNode,
                                                     $currentSectionLevel,
                                                     $listSectionLevel,
                                                     $noParagraphs );
            }break;

            case 'section' :
            {
                $listSectionLevel += 1;
                $output .= $this->inputSectionXML( $listNode, $currentSectionLevel, $listSectionLevel );
            }break;

            case '#text' :
            {
                //ignore whitespace
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", __METHOD__ );
            }break;
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given table cell
    */
    function &inputTdXML( &$tdNode, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = '';
        $tagName = $tdNode instanceof DOMNode ? $tdNode->nodeName : '';

        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= $this->inputParagraphXML( $tdNode, $currentSectionLevel, $tdSectionLevel  );
            }break;

            case 'section' :
            {
                $tdSectionLevel += 1;
                $output .= $this->inputSectionXML( $tdNode, $currentSectionLevel, $tdSectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", __METHOD__ );
            }break;
        }
        return $output;
    }

    /*!
     \return the input xml of the given paragraph
    */
    function &inputParagraphXML( &$paragraph,
                                  $currentSectionLevel,
                                  $tdSectionLevel = null,
                                  $noRender = false )
    {
        $output = '';
        $children = $paragraph->childNodes;
        if ( $noRender )
        {
            foreach ( $children as $child )
            {
                $output .= $this->inputTagXML( $child, $currentSectionLevel, $tdSectionLevel );
            }
            return $output;
        }

        $paragraphClassName = $paragraph->getAttribute( 'class' );
        $paragraphAlign = $paragraph->getAttribute( 'align' );
        $customAttributePart = self::getCustomAttrPart( $paragraph, $styleString );

        if ( $paragraphAlign )
        {
            $customAttributePart .= ' align="' . $paragraphAlign . '"';
        }

        if ( $paragraphClassName )
        {
            $customAttributePart .= ' class="' . $paragraphClassName . '"';
        }

        $openPara = "<p$customAttributePart$styleString>";
        $closePara = '</p>';

        if ( $children->length == 0 )
        {
            $output = $openPara . $closePara;
            return $output;
        }

        $lastChildInline = null;
        $innerContent = '';
        foreach ( $children as $child )
        {
            $childOutput = $this->inputTagXML( $child, $currentSectionLevel, $tdSectionLevel );

            // Some tags in xhtml aren't allowed as child of paragraph
            $inline = !( $child->nodeName === 'ul'
                      || $child->nodeName === 'ol'
                      || $child->nodeName === 'literal'
                      || ( $child->nodeName === 'custom'
                        && !self::customTagIsInline( $child->getAttribute( 'name' ) ) )
                      || ( $child->nodeName === 'embed'
                        && !self::embedTagIsCompatibilityMode()
                        && !self::embedTagIsImageByNode( $child ) )
                      );
            if ( $inline )
            {
                $innerContent .= $childOutput;
            }


            if ( ( !$inline && $lastChildInline ) ||
                 ( $inline && !$child->nextSibling ) )
            {
                $output .= $openPara . $innerContent . $closePara;
                $innerContent = '';
            }

            if ( !$inline )
            {
                $output .= $childOutput;
            }

            $lastChildInline = $inline;
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $output, __METHOD__ . ' output' );
        return $output;
    }

    /*!
     \return the input xml for the given tag
     \as in the xhtml used inside the editor
    */
    function &inputTagXML( &$tag, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output       = '';
        $tagName      = $tag instanceof DOMNode ? $tag->nodeName : '';
        $childTagText = '';

        // render children tags
        if ( $tag->hasChildNodes() )
        {
            $tagChildren = $tag->childNodes;
            foreach ( $tagChildren as $childTag )
            {
                $childTagText .= $this->inputTagXML( $childTag, $currentSectionLevel, $tdSectionLevel );
            }
        }
        switch ( $tagName )
        {
            case '#text' :
            {
                $tagContent = $tag->textContent;
                if ( !strlen( $tagContent ) )
                {
                    break;
                }

                $tagContent = htmlspecialchars( $tagContent );
                $tagContent = str_replace ( '&amp;nbsp;', '&nbsp;', $tagContent );

                if ( $this->allowMultipleSpaces )
                {
                    $tagContent = str_replace( '  ', ' &nbsp;', $tagContent );
                }
                else
                {
                    $tagContent = preg_replace( "/ {2,}/", ' ', $tagContent );
                }

                if ( $tagContent[0] === ' ' && !$tag->previousSibling )//- Fixed "first space in paragraph" issue (ezdhtml rev.12246)
                {
                    $tagContent[0] = ';';
                    $tagContent = '&nbsp' . $tagContent;
                }

                if ( $this->allowNumericEntities )
                    $tagContent = preg_replace( '/&amp;#([0-9]+);/', '&#\1;', $tagContent );

                $output .= $tagContent;

            }break;

            case 'embed' :
            case 'embed-inline' :
            {
                $view      = $tag->getAttribute( 'view' );
                $size      = $tag->getAttribute( 'size' );
                $alignment = $tag->getAttribute( 'align' );
                $objectID  = $tag->getAttribute( 'object_id' );
                $nodeID    = $tag->getAttribute( 'node_id' );
                $showPath  = $tag->getAttribute( 'show_path' );
                $htmlID    = $tag->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'id' );
                $className = $tag->getAttribute( 'class' );
                $idString  = '';
                $tplSuffix = '';

                if ( !$size )
                {
                    $contentIni = eZINI::instance( 'content.ini' );
                    $size       = $contentIni->variable( 'ImageSettings', 'DefaultEmbedAlias' );
                }

                if ( !$view )
                {
                    $view = $tagName;
                }

                $objectAttr = '';
                $objectAttr .= ' alt="' . $size . '"';
                $objectAttr .= ' view="' . $view . '"';

                if ( $htmlID != '' )
                {
                    $objectAttr .= ' html_id="' . $htmlID . '"';
                }
                if ( $showPath === 'true' )
                {
                    $objectAttr .= ' show_path="true"';
                }

                if ( $tagName === 'embed-inline' )
                    $objectAttr .= ' inline="true"';
                else
                    $objectAttr .= ' inline="false"';

                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );
                $object              = false;

                if ( is_numeric( $objectID ) )
                {
                    $object = eZContentObject::fetch( $objectID );
                    $idString = 'eZObject_' . $objectID;
                }
                elseif ( is_numeric( $nodeID ) )
                {
                    $node      = eZContentObjectTreeNode::fetch( $nodeID );
                    $object    = $node instanceof eZContentObjectTreeNode ? $node->object() : false;
                    $idString  = 'eZNode_' . $nodeID;
                    $tplSuffix = '_node';
                }

                if ( $object instanceof eZContentObject )
                {
                    $objectName = $object->attribute( 'name' );
                    $classIdentifier = $object->attribute( 'class_identifier' );
                    if ( !$object->attribute( 'can_read' ) ||
                         !$object->attribute( 'can_view_embed' ) )
                    {
                        $tplSuffix = '_denied';
                    }
                    else if ( $object->attribute( 'status' ) == eZContentObject::STATUS_ARCHIVED )
                    {
                        $className .= ' ezoeItemObjectInTrash';
                        if ( self::$showEmbedValidationErrors )
                        {
                            $oeini = eZINI::instance( 'ezoe.ini' );
                            if ( $oeini->variable('EditorSettings', 'ValidateEmbedObjects' ) === 'enabled' )
                                $className .= ' ezoeItemValidationError';
                        }
                    }
                }
                else
                {
                    $objectName = 'Unknown';
                    $classIdentifier = false;
                    $tplSuffix = '_denied';
                    $className .= ' ezoeItemObjectDeleted';
                    if ( self::$showEmbedValidationErrors )
                    {
                        $className .= ' ezoeItemValidationError';
                    }
                }

                $embedContentType = self::embedTagContentType( $classIdentifier );
                if ( $embedContentType === 'images' )
                {
                    $ini = eZINI::instance();
                    $URL = self::getServerURL();
                    $objectAttributes = $object->contentObjectAttributes();
                    $imageDatatypeArray = $ini->variable('ImageDataTypeSettings', 'AvailableImageDataTypes');
                    $imageWidth = 32;
                    $imageHeight = 32;
                    foreach ( $objectAttributes as $objectAttribute )
                    {
                        $classAttribute = $objectAttribute->contentClassAttribute();
                        $dataTypeString = $classAttribute->attribute( 'data_type_string' );
                        if ( in_array ( $dataTypeString, $imageDatatypeArray ) && $objectAttribute->hasContent() )
                        {
                            $content = $objectAttribute->content();
                            if ( $content == null )
                                continue;

                            if ( $content->hasAttribute( $size ) )
                            {
                                $imageAlias  = $content->imageAlias( $size );
                                $srcString   = $URL . '/' . $imageAlias['url'];
                                $imageWidth  = $imageAlias['width'];
                                $imageHeight = $imageAlias['height'];
                                break;
                            }
                            else
                            {
                                eZDebug::writeError( "Image alias does not exist: $size, missing from image.ini?",
                                    __METHOD__ );
                            }
                        }
                    }

                    if ( !isset( $srcString ) )
                    {
                        $srcString = self::getDesignFile('images/tango/mail-attachment32.png');
                    }

                    if ( $alignment === 'center' )
                        $objectAttr .= ' align="middle"';
                    else if ( $alignment )
                        $objectAttr .= ' align="' . $alignment . '"';

                    if ( $className != '' )
                        $objectAttr .= ' class="' . $className . '"';

                    $output .= '<img id="' . $idString . '" title="' . $objectName . '" src="' .
                               $srcString . '" width="' . $imageWidth . '" height="' . $imageHeight .
                               '" ' . $objectAttr . $customAttributePart . $styleString . ' />';
                }
                else if ( self::embedTagIsCompatibilityMode() )
                {
                    $srcString = self::getDesignFile('images/tango/mail-attachment32.png');
                    if ( $alignment === 'center' )
                        $objectAttr .= ' align="middle"';
                    else if ( $alignment )
                        $objectAttr .= ' align="' . $alignment . '"';

                    if ( $className != '' )
                        $objectAttr .= ' class="' . $className . '"';

                    $output .= '<img id="' . $idString . '" title="' . $objectName . '" src="' .
                               $srcString . '" width="32" height="32" ' . $objectAttr .
                               $customAttributePart . $styleString . ' />';
                }
                else
                {
                    if ( $alignment )
                        $objectAttr .= ' align="' . $alignment . '"';

                    if ( $className )
                        $objectAttr .= ' class="ezoeItemNonEditable ' . $className . ' ezoeItemContentType' .
                                       ucfirst( $embedContentType ) . '"';
                    else
                        $objectAttr .= ' class="ezoeItemNonEditable ezoeItemContentType' .
                                       ucfirst( $embedContentType ) . '"';

                    if ( $tagName === 'embed-inline' )
                        $htmlTagName = 'span';
                    else
                        $htmlTagName = 'div';

                    $objectParam = array( 'size' => $size, 'align' => $alignment, 'show_path' => $showPath );
                    if ( $htmlID ) $objectParam['id'] = $htmlID;

                    $res = eZTemplateDesignResource::instance();
                    $res->setKeys( array( array('classification', $className) ) );

                    if ( isset( $node ) )
                    {
                        $templateOutput = self::fetchTemplate( 'design:content/datatype/view/ezxmltags/' . $tagName . $tplSuffix . '.tpl', array(
                            'view' => $view,
                            'object' => $object,
                            'link_parameters' => array(),
                            'classification' => $className,
                            'object_parameters' => $objectParam,
                            'node' => $node,
                        ));
                    }
                    else
                    {
                        $templateOutput = self::fetchTemplate( 'design:content/datatype/view/ezxmltags/' . $tagName . $tplSuffix . '.tpl', array(
                            'view' => $view,
                            'object' => $object,
                            'link_parameters' => array(),
                            'classification' => $className,
                            'object_parameters' => $objectParam,
                        ));
                    }

                    $output .= '<' . $htmlTagName . ' id="' . $idString . '" title="' . $objectName . '"' .
                               $objectAttr . $customAttributePart . $styleString . '>' . $templateOutput .
                               '</' . $htmlTagName . '>';
                }
            }break;

            case 'custom' :
            {
                $name = $tag->getAttribute( 'name' );
                $align = $tag->getAttribute( 'align' );
                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );
                $inline = self::customTagIsInline( $name );
                if ( $align )
                {
                    $customAttributePart .= ' align="' . $align . '"';
                }

                if ( isset( self::$nativeCustomTags[ $name ] ))
                {
                    if ( !$childTagText ) $childTagText = '&nbsp;';
                    $output .= '<' . self::$nativeCustomTags[ $name ] . $customAttributePart . $styleString .
                               '>' . $childTagText . '</' . self::$nativeCustomTags[ $name ] . '>';
                }
                else if ( $inline === true )
                {
                    if ( !$childTagText ) $childTagText = '&nbsp;';
                    $output .= '<span class="ezoeItemCustomTag ' . $name . '" type="custom"' .
                               $customAttributePart . $styleString . '>' . $childTagText . '</span>';
                }
                else if ( $inline )
                {
                    $imageUrl = self::getCustomAttrbute( $tag, 'image_url' );
                    if ( $imageUrl === null || !$imageUrl )
                    {
                        $imageUrl = self::getDesignFile( $inline );
                        $customAttributePart .= ' width="22" height="22"';
                    }
                    $output .= '<img src="' . $imageUrl . '" class="ezoeItemCustomTag ' . $name .
                               '" type="custom"' . $customAttributePart . $styleString . ' />';
                }
                else
                {
                    $customTagContent = $this->inputSectionXML( $tag, $currentSectionLevel, $tdSectionLevel );
                    /*foreach ( $tag->childNodes as $tagChild )
                    {
                        $customTagContent .= $this->inputTdXML( $tagChild,
                                                                $currentSectionLevel,
                                                                $tdSectionLevel );
                    }*/
                    $output .= '<div class="ezoeItemCustomTag ' . $name . '" type="custom"' .
                               $customAttributePart . $styleString . '>' . $customTagContent . '</div>';
                }
            }break;

            case 'literal' :
            {
                $literalText = '';
                foreach ( $tagChildren as $childTag )
                {
                    $literalText .= $childTag->textContent;
                }
                $className = $tag->getAttribute( 'class' );

                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );

                $literalText = htmlspecialchars( $literalText );
                $literalText = str_replace( "\n", '<br />', $literalText );

                if ( $className != '' )
                    $customAttributePart .= ' class="' . $className . '"';

                $output .= '<pre' . $customAttributePart . $styleString . '>' . $literalText . '</pre>';

            }break;

            case 'ul' :
            case 'ol' :
            {
                $listContent = '';

                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );

                // find all list elements
                foreach ( $tag->childNodes as $listItemNode )
                {
                    if ( !$listItemNode instanceof DOMElement )
                    {
                        continue;// ignore whitespace
                    }

                    $LIcustomAttributePart = self::getCustomAttrPart( $listItemNode, $listItemStyleString );

                    $noParagraphs = self::childTagCount( $listItemNode ) <= 1;
                    $listItemContent = '';
                    foreach ( $listItemNode->childNodes as $itemChildNode )
                    {
                        $listSectionLevel = $currentSectionLevel;
                        if ( $itemChildNode instanceof DOMNode
                        && ( $itemChildNode->nodeName === 'section'
                          || $itemChildNode->nodeName === 'paragraph' ) )
                        {
                            $listItemContent .= $this->inputListXML( $itemChildNode,
                                                                     $currentSectionLevel,
                                                                     $listSectionLevel,
                                                                     $noParagraphs );
                        }
                        else
                        {
                            $listItemContent .= $this->inputTagXML( $itemChildNode,
                                                                    $currentSectionLevel,
                                                                    $tdSectionLevel );
                        }
                    }

                    $LIclassName = $listItemNode->getAttribute( 'class' );

                    if ( $LIclassName )
                        $LIcustomAttributePart .= ' class="' . $LIclassName . '"';

                    $listContent .= '<li' . $LIcustomAttributePart . $listItemStyleString . '>' .
                                    $listItemContent . '</li>';
                }
                $className = $tag->getAttribute( 'class' );
                if ( $className != '' )
                    $customAttributePart .= ' class="' . $className . '"';

                $output .= '<' . $tagName . $customAttributePart . $styleString . '>' . $listContent . '</' .
                           $tagName . '>';
            }break;

            case 'table' :
            {
                $tableRows = '';
                $border = $tag->getAttribute( 'border' );
                $width = $tag->getAttribute( 'width' );
                $align = $tag->getAttribute( 'align' );
                $tableClassName = $tag->getAttribute( 'class' );

                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );

                // find all table rows
                foreach ( $tag->childNodes as $tableRow )
                {
                    if ( !$tableRow instanceof DOMElement )
                    {
                        continue; // ignore whitespace
                    }
                    $TRcustomAttributePart = self::getCustomAttrPart( $tableRow, $tableRowStyleString );
                    $TRclassName = $tableRow->getAttribute( 'class' );

                    $tableData = '';
                    foreach ( $tableRow->childNodes as $tableCell )
                    {
                        if ( !$tableCell instanceof DOMElement )
                        {
                            continue; // ignore whitespace
                        }

                        $TDcustomAttributePart = self::getCustomAttrPart( $tableCell, $tableCellStyleString );

                        $className = $tableCell->getAttribute( 'class' );
                        $cellAlign = $tableCell->getAttribute( 'align' );

                        $colspan = $tableCell->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/',
                                                               'colspan' );
                        $rowspan = $tableCell->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/',
                                                               'rowspan' );
                        $cellWidth = $tableCell->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/',
                                                                 'width' );
                        if ( $className != '' )
                        {
                            $TDcustomAttributePart .= ' class="' . $className . '"';
                        }
                        if ( $cellWidth != '' )
                        {
                            $TDcustomAttributePart .= ' width="' . $cellWidth . '"';
                        }
                        if ( $colspan && $colspan !== '1' )
                        {
                            $TDcustomAttributePart .= ' colspan="' . $colspan . '"';
                        }
                        if ( $rowspan && $rowspan !== '1' )
                        {
                            $TDcustomAttributePart .= ' rowspan="' . $rowspan . '"';
                        }
                        if ( $cellAlign )
                        {
                                $TDcustomAttributePart .= ' align="' . $cellAlign . '"';
                        }
                        $cellContent = '';
                        $tdSectionLevel = $currentSectionLevel;
                        foreach ( $tableCell->childNodes as $tableCellChildNode )
                        {
                            $cellContent .= $this->inputTdXML( $tableCellChildNode,
                                                               $currentSectionLevel,
                                                               $tdSectionLevel - $currentSectionLevel );
                        }
                        if ( $cellContent === '' )
                        {
                            // tinymce has some issues with empty content in some browsers
                            $cellContent = '<br mce_bogus="1" />';
                        }
                        if ( $tableCell->nodeName === 'th' )
                        {
                            $tableData .= '<th' . $TDcustomAttributePart . $tableCellStyleString . '>' .
                                          $cellContent . '</th>';
                        }
                        else
                        {
                            $tableData .= '<td' . $TDcustomAttributePart . $tableCellStyleString . '>' .
                                          $cellContent . '</td>';
                        }
                    }
                    if ( $TRclassName )
                        $TRcustomAttributePart .= ' class="' . $TRclassName . '"';

                    $tableRows .= '<tr' . $TRcustomAttributePart . $tableRowStyleString . '>' .
                                  $tableData . '</tr>';
                }
                //if ( self::browserSupportsDHTMLType() === 'Trident' )
                //{
                    $customAttributePart .= ' width="' . $width . '"';
                /*}
                else
                {
                    // if this is reenabled merge it with $styleString
                    $customAttributePart .= ' style="width:' . $width . ';"';
                }*/

                if ( is_string( $border ) )
                {
                    $customAttributePart .= ' border="' . $border . '"';
                }

                if ( $align )
                {
                    $customAttributePart .= ' align="' . $align . '"';
                }

                if ( $tableClassName )
                {
                    $customAttributePart .= ' class="' . $tableClassName . '"';
                }

                $output .= '<table' . $customAttributePart . $styleString . '><tbody>' . $tableRows .
                           '</tbody></table>';
            }break;

            // normal content tags
            case 'emphasize' :
            {
                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );

                $className = $tag->getAttribute( 'class' );
                if ( $className )
                {
                    $customAttributePart .= ' class="' . $className . '"';
                }
                $output .= '<em' . $customAttributePart . $styleString . '>' . $childTagText  . '</em>';
            }break;

            case 'strong' :
            {
                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );

                $className = $tag->getAttribute( 'class' );
                if ( $className  )
                {
                    $customAttributePart .= ' class="' . $className . '"';
                }
                $output .= '<strong' . $customAttributePart . $styleString . '>' . $childTagText  . '</strong>';
            }break;

            case 'line' :
            {
                $output .= $childTagText . '<br />';
            }break;

            case 'anchor' :
            {
                $name = $tag->getAttribute( 'name' );

                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );

                $output .= '<a name="' . $name . '" class="mceItemAnchor"' . $customAttributePart .
                           $styleString . '></a>';
            }break;

            case 'link' :
            {
                $customAttributePart = self::getCustomAttrPart( $tag, $styleString );

                $linkID = $tag->getAttribute( 'url_id' );
                $target = $tag->getAttribute( 'target' );
                $className = $tag->getAttribute( 'class' );
                $viewName = $tag->getAttribute( 'view' );
                $objectID = $tag->getAttribute( 'object_id' );
                $nodeID = $tag->getAttribute( 'node_id' );
                $anchorName = $tag->getAttribute( 'anchor_name' );
                $showPath = $tag->getAttribute( 'show_path' );
                $htmlID = $tag->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'id' );
                $htmlTitle = $tag->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'title' );
                $attributes = array();

                if ( $objectID != null )
                {
                    $href = 'ezobject://' .$objectID;
                }
                elseif ( $nodeID != null )
                {
                    if ( $showPath === 'true' )
                    {
                        $node = eZContentObjectTreeNode::fetch( $nodeID );
                        $href = $node ?
                                'eznode://' . $node->attribute('path_identification_string') :
                                'eznode://' . $nodeID;
                    }
                    else
                    {
                        $href = 'eznode://' . $nodeID;
                    }
                }
                elseif ( $linkID != null )
                {
                    $href = eZURL::url( $linkID );
                }
                else
                {
                    $href = $tag->getAttribute( 'href' );
                }

                if ( $anchorName != null )
                {
                    $href .= '#' . $anchorName;
                }

                if ( $className != '' )
                {
                    $attributes[] = 'class="' . $className . '"';
                }

                if ( $viewName != '' )
                {
                    $attributes[] = 'view="' . $viewName . '"';
                }

                $attributes[] = 'href="' . $href . '"';
                // Also set mce_href for use by OE to make sure href attribute is not messed up by IE 6 / 7
                $attributes[] = 'mce_href="' . $href . '"';
                if ( $target != '' )
                {
                    $attributes[] = 'target="' . $target . '"';
                }
                if ( $htmlTitle != '' )
                {
                    $attributes[] = 'title="' . $htmlTitle . '"';
                }
                if ( $htmlID != '' )
                {
                   $attributes[] = 'id="' . $htmlID . '"';
                }

                $attributeText = '';
                if ( !empty( $attributes ) )
                {
                    $attributeText = ' ' .implode( ' ', $attributes );
                }
                $output .= '<a' . $attributeText . $customAttributePart . $styleString . '>' .
                           $childTagText . '</a>';
            }break;
            case 'tr' :
            case 'td' :
            case 'th' :
            case 'li' :
            case 'paragraph' :
            {
            }break;
            default :
            {

            }break;
        }
        return $output;
    }

    /*
     * Generates custom attribute value, and also sets tag styles to styleString variable (by ref)
     */
    public static function getCustomAttrPart( $tag, &$styleString )
    {
        $customAttributePart = '';
        $styleString         = '';

        if ( self::$customAttributeStyleMap === null )
        {
            // Filtered styles because the browser (ie,ff&opera) convert span tag to
            // font tag in certain circumstances
            $oeini = eZINI::instance( 'ezoe.ini' );
            $styles = $oeini->variable('EditorSettings', 'CustomAttributeStyleMap' );
            $customAttributeStyleMap = array();
            foreach( $styles as $name => $style )
            {
                if ( preg_match("/(margin|border|padding|width|height)/", $style ) )
                {
                    self::$customAttributeStyleMap[$name] = $style;
                }
                else
                {
                    eZDebug::writeWarning( "Style not valid: $style, see ezoe.ini[EditorSettings]CustomAttributeStyleMap",
                                           __METHOD__ );
                }
            }
        }

        // generate custom attribute value
        foreach ( $tag->attributes as $attribute )
        {
            if ( $attribute->namespaceURI == 'http://ez.no/namespaces/ezpublish3/custom/' )
            {
                if ( $customAttributePart === '' )
                {
                    $customAttributePart = ' customattributes="';
                    $customAttributePart .= $attribute->name . '|' . $attribute->value;
                }
                else
                {
                   $customAttributePart .= 'attribute_separation' . $attribute->name . '|' .
                                           $attribute->value;
                }
                if ( isset( self::$customAttributeStyleMap[$attribute->name] ) )
                {
                    $styleString .= self::$customAttributeStyleMap[$attribute->name] . ': ' .
                                    $attribute->value . '; ';
                }
            }
        }

        if ( $customAttributePart !== '' )
        {
            $customAttributePart .= '"';
        }
        if ( $styleString !== '' )
        {
            $styleString = ' style="' . $styleString . '"';
        }
        return $customAttributePart;
    }

    /*
     * Get custom attribute value
     */
    public static function getCustomAttrbute( $tag, $attributeName )
    {
        foreach ( $tag->attributes as $attribute )
        {
            if ( $attribute->name === $attributeName
              && $attribute->namespaceURI === 'http://ez.no/namespaces/ezpublish3/custom/' )
            {
                return $attribute->value;
            }
        }
        return null;
    }

    /*
     * Get server url in relative or absolute format depending on ezoe settings.
     */
    public static function getServerURL()
    {
        if ( self::$serverURL === null  )
        {
            $oeini = eZINI::instance( 'ezoe.ini' );
            if ( $oeini->hasVariable( 'SystemSettings', 'RelativeURL' ) &&
                 $oeini->variable( 'SystemSettings', 'RelativeURL' ) === 'enabled' )
            {
                self::$serverURL = eZSys::wwwDir();
                if ( self::$serverURL === '/'  )
                    self::$serverURL = '';
            }
            else
            {
                $domain = eZSys::hostname();
                $protocol = 'http';

                // Default to https if SSL is enabled
                // Check if SSL port is defined in site.ini
                $sslPort = 443;
                $ini = eZINI::instance();
                if ( $ini->hasVariable( 'SiteSettings', 'SSLPort' ) )
                    $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );

                if ( eZSys::serverPort() == $sslPort )
                    $protocol = 'https';

                self::$serverURL = $protocol . '://' . $domain . eZSys::wwwDir();
            }
        }
        return self::$serverURL;
    }

    /*
     * Get design file (template) for use in embed tags
     */
    public static function getDesignFile( $file, $triedFiles = array() )
    {
        if ( self::$designBases === null )
        {
            self::$designBases = eZTemplateDesignResource::allDesignBases();
        }

        $match = eZTemplateDesignResource::fileMatch( self::$designBases, '', $file, $triedFiles );

        if ( $match === false )
        {
            eZDebug::writeWarning( "Could not find: $file", __METHOD__ );
            return $file;
        }
        return htmlspecialchars( self::getServerURL() . '/' . $match['path'] );
    }

    /**
     * Figgure out if a custom tag is inline or not based on content.ini settings
     *
     * @param string $name Tag name
     * @return bool|string Return 'image' if tag is inline image, otherwise true/false.
     */
    public static function customTagIsInline( $name )
    {
        if ( self::$customInlineTagList === null )
        {
            $ini = eZINI::instance( 'content.ini' );
            self::$customInlineTagList = $ini->variable( 'CustomTagSettings', 'IsInline' );
            self::$customInlineIconPath = $ini->hasVariable( 'CustomTagSettings', 'InlineImageIconPath' ) ?
                                          $ini->variable( 'CustomTagSettings', 'InlineImageIconPath' ) :
                                          array();
        }

        if ( isset( self::$customInlineTagList[ $name ] ) )
        {
            if ( self::$customInlineTagList[ $name ] === 'true' )
            {
                return true;
            }
            else if ( self::$customInlineTagList[ $name ] === 'image' )
            {
                if ( isset( self::$customInlineIconPath[ $name ] ) )
                    return self::$customInlineIconPath[ $name ];
                else
                    return 'images/tango/image-x-generic22.png';
            }
        }
        return false;
    }

    /*
     * Find out if embed object is image type or not.
     */
    public static function embedTagIsImageByNode( $xmlNode )
    {
        $objectID  = $xmlNode->getAttribute( 'object_id' );
        $nodeID    = $xmlNode->getAttribute( 'node_id' );
        $object    = false;
        $classIdentifier = false;

        if ( is_numeric( $objectID ) )
        {
            $object = eZContentObject::fetch( $objectID );
        }
        elseif ( is_numeric( $nodeID ) )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node instanceof eZContentObjectTreeNode )
                $object = $node->object();
        }

        if ( $object instanceof eZContentObject )
        {
            $classIdentifier = $object->attribute( 'class_identifier' );
        }

        return $classIdentifier && self::embedTagContentType(  $classIdentifier ) === 'images';
    }

    /*
     * Get content type by class identifier
     */
    public static function embedTagContentType( $classIdentifier  )
    {
        $contentIni = eZINI::instance('content.ini');

        foreach ( $contentIni->variable( 'RelationGroupSettings', 'Groups' ) as $group )
        {
            $settingName = ucfirst( $group ) . 'ClassList';
            if ( $contentIni->hasVariable( 'RelationGroupSettings', $settingName ) )
            {
                if ( in_array( $classIdentifier, $contentIni->variable( 'RelationGroupSettings', $settingName ) ) )
                    return $group;
            }
            else
                eZDebug::writeDebug( "Missing content.ini[RelationGroupSettings]$settingName setting.",
                                     __METHOD__ );
        }

        return $contentIni->variable( 'RelationGroupSettings', 'DefaultGroup' );
    }

    /*
     * Return if embed tags should be displayed in compatibility mode, as in like the old editor using attachment icons.
     */
    public static function embedTagIsCompatibilityMode()
    {
        if ( self::$embedIsCompatibilityMode === null )
        {
            $ezoeIni = eZINI::instance('ezoe.ini');
            self::$embedIsCompatibilityMode = $ezoeIni->variable('EditorSettings', 'CompatibilityMode' ) === 'enabled';
        }
        return self::$embedIsCompatibilityMode;
    }

    /* Count child elements, ignoring whitespace and text
     *
     * @param DOMElement $parent
     * @return int
     */
    protected static function childTagCount( DOMElement $parent )
    {
        $count = 0;
        foreach( $parent->childNodes as $child )
        {
            if ( $child instanceof DOMElement ) $count++;
        }
        return $count;
    }

    /* Execute template cleanly, make sure we don't override parameters
     * and back them up for setting them back when done.
     *
     * @param string $template
     * @param array $parameters Hash with name and value
     * @return string
     */
    protected static function fetchTemplate( $template, $parameters = array() )
    {
        $tpl = eZTemplate::factory();
        $existingPramas = array();

        foreach( $parameters as $name => $value )
        {
            if ( $tpl->hasVariable( $name ) )
                $existingPramas[$name] = $tpl->variable( $name );

            $tpl->setVariable( $name, $value );
        }

        $result = $tpl->fetch( $template );

        foreach( $parameters as $name => $value )
        {
            if ( isset( $existingPramas[$name] ) )
                $tpl->setVariable( $name, $existingPramas[$name] );
            else
                $tpl->unsetVariable( $name );
        }

        return $result;
    }

    protected static $serverURL   = null;
    protected static $browserType = null;
    protected static $designBases = null;
    protected static $userAccessHash = array();
    protected static $customInlineTagList = null;
    protected static $customInlineIconPath = null;
    protected static $customAttributeStyleMap = null;
    protected static $embedIsCompatibilityMode = null;
    protected static $xmlTagAliasList = null;

    protected $editorLayoutSettings = null;
    protected static $editorGlobalLayoutSettings = null;

    protected static $showEmbedValidationErrors = null;

    public $LineTagArray = array( 'emphasize', 'strong', 'link', 'a', 'em', 'i', 'b', 'bold', 'anchor' );

    /// Contains the XML data
    public $XMLData;

    public $IsStrictHeader = false;
    public $SectionArray = array(  'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'section' );

    public $eZPublishVersion;

    public $allowMultipleSpaces = true;
    protected $allowNumericEntities = false;
}

?>
