<?php
//
// Definition of eZNavigationPart class
//
// Created on: <18-Feb-2003 11:38:57 bf>
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

/*!
  \class eZNavigationPart eznavigationpart.php
  \brief eZNavigationPart handles grouping of functions across modules
  \ingroup eZKernel

  A navigation part is a group of functions which belongs together. Every view can
  return the navigation part it should use. It is up to the view to return the
  proper navigation part. Views can internally check which navigation part to use,
  in the case of content/view the view will check the navigation part set in
  the section setup and use this.

  If the view does not return any navigation part it will default to the Content part.

  The navigation parts are controlled by the \c menu.ini file, look for the
  \c NavigationPart group.
  You can easily add new entries in override files or in extensions by adding
  to the \c Part list.

*/

class eZNavigationPart
{
    /*!
     \static
     Will return the navigation part array if the identifier is valid,
     the default will be returned if the identifier is not valid.

     The navigation parts are defined in the INI file \c menu.ini
     under the \c NavigationPart group.
    */
    static function fetchPartByIdentifier( $identifier )
    {
        $parts = eZNavigationPart::fetchList();

        if ( isset( $parts[$identifier] ) )
            return $parts[$identifier];

        // Return the first part which is the default
        if ( isset( $parts[0] ) )
            return $parts[0];

        return false;
    }

    /*!
     \static
     \return The current list of navigation part identifiers

     \note The list is cached in the global variable \c eZNavigationPartList.
    */
    static function fetchList()
    {
        $list =& $GLOBALS['eZNavigationPartList'];
        if ( isset( $list ) )
            return $list;

        $ini = eZINI::instance( 'menu.ini' );
        $parts = $ini->variable( 'NavigationPart', 'Part' );
        $list = array();
        foreach ( $parts as $identifier => $name )
        {
            $list[$identifier] = array( 'name' => ezpI18n::tr( 'kernel/navigationpart', $name, 'Navigation part' ),
                                        'identifier' => $identifier );
        }
        return $list;
    }

    /*!
     \private
     \note This funtion only exists for the i18n entries to be picked up by ezlupdate.
    */
    private static function i18nDummy()
    {
        ezpI18n::tr( 'kernel/navigationpart', 'Content structure', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Media library', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'User accounts', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Webshop', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Design', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'Setup', 'Navigation part' );
        ezpI18n::tr( 'kernel/navigationpart', 'My account', 'Navigation part' );
    }

}

?>
