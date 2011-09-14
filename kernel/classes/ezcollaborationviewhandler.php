<?php
//
// Definition of eZCollaborationViewHandler class
//
// Created on: <23-Jan-2003 11:59:34 amos>
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

/*! \file
*/

/*!
  \class eZCollaborationViewHandler ezcollaborationviewhandler.php
  \brief The class eZCollaborationViewHandler does

*/

class eZCollaborationViewHandler
{
    const TYPE_STANDARD = 1;
    const TYPE_GROUP = 2;

    /*!
     Initializes the view mode.
    */
    function eZCollaborationViewHandler( $viewMode, $viewType )
    {
        $this->ViewMode = $viewMode;
        $this->ViewType = $viewType;
        $this->TemplateName = $viewMode;
        $ini = $this->ini();
        if ( $viewType == self::TYPE_STANDARD )
        {
            $this->TemplatePrefix = "design:collaboration/view/";
            $viewGroup = $viewMode . "View";
        }
        else if ( $viewType == self::TYPE_GROUP )
        {
            $this->TemplatePrefix = "design:collaboration/group/view/";
            $viewGroup = $viewMode . "GroupView";
        }
        if ( $ini->hasGroup( $viewGroup ) )
        {
            if ( $ini->hasVariable( $viewGroup, 'TemplateName' ) )
                $this->TemplateName = $ini->variable( $viewGroup, 'TemplateName' );
        }
    }

    /*!
     \return the template which is used for viewing the collaborations.
    */
    function template()
    {
        return $this->TemplatePrefix . $this->TemplateName . ".tpl";
    }

    /*!
     \static
     \return the ini object for collaboration.ini
    */
    static function ini()
    {
        return eZINI::instance( 'collaboration.ini' );
    }

    /*!
     \static
     \return true if the viewmode \a $viewMode exists with the current configuration
    */
    static function exists( $viewMode )
    {
        $list = eZCollaborationViewHandler::fetchList();
        return in_array( $viewMode, $list );
    }

    /*!
     \static
     \return true if the viewmode \a $viewMode exists for groups with the current configuration
    */
    static function groupExists( $viewMode )
    {
        $list = eZCollaborationViewHandler::fetchGroupList();
        return in_array( $viewMode, $list );
    }

    /*!
     \static
     \return a list of active viewmodes.
    */
    static function fetchList()
    {
        return eZCollaborationViewHandler::ini()->variable( 'ViewSettings', 'ViewList' );
    }

    /*!
     \static
     \return a list of active viewmodes for groups.
    */
    static function fetchGroupList()
    {
        return eZCollaborationViewHandler::ini()->variable( 'ViewSettings', 'GroupViewList' );
    }

    /**
     * Returns a shared instance of the eZCollaborationViewHandler class
     * pr the two input params.
     *
     *
     * @param string $viewMode
     * @param int $type Is self::TYPE_STANDARD by default
     * @return eZCollaborationViewHandler
     */
    static function instance( $viewMode, $type = self::TYPE_STANDARD )
    {
        if ( $type == self::TYPE_STANDARD )
            $instance =& $GLOBALS["eZCollaborationView"][$viewMode];
        else if ( $type == self::TYPE_GROUP )
            $instance =& $GLOBALS["eZCollaborationGroupView"][$viewMode];
        else
        {
            return null;
        }
        if ( !isset( $instance ) )
        {
            $instance = new eZCollaborationViewHandler( $viewMode, $type );
        }
        return $instance;
    }

    /// \privatesection
    /// The viewmode
    public $ViewMode;
    public $ViewType;
    public $TemplateName;
    public $TemplatePrefix;
}

?>
