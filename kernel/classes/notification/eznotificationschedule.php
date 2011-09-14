<?php
//
// Definition of eZNotificationSchedule class
//
// Created on: <16-May-2003 15:22:43 sp>
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
  \class eZNotificationSchedule eznotificationschedule.php
  \brief The class eZNotificationSchedule does

*/

class eZNotificationSchedule
{
    /*!
     Constructor
    */
    function eZNotificationSchedule()
    {
    }

    static function setDateForItem( $item, $settings )
    {
        if ( !is_array( $settings ) )
            return false;

        $dayNum = isset( $settings['day'] ) ? $settings['day'] : false;
        $hour = $settings['hour'];
        $currentDate = getdate();
        $hoursDiff = $hour - $currentDate['hours'];

        switch ( $settings['frequency'] )
        {
            case 'day':
            {
                if ( $hoursDiff <= 0 )
                {
                    $hoursDiff += 24;
                }

                $secondsDiff = 3600 * $hoursDiff
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;

            case 'week':
            {
                $daysDiff = $dayNum - $currentDate['wday'];
                if ( $daysDiff < 0 or
                     ( $daysDiff == 0 and $hoursDiff <= 0 ) )
                {
                    $daysDiff += 7;
                }

                $secondsDiff = 3600 * ( $daysDiff * 24 + $hoursDiff )
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;

            case 'month':
            {
                // If the daynum the user has chosen is larger than the number of days in this month,
                // then reduce it to the number of days in this month.
                $daysInMonth = intval( date( 't', mktime( 0, 0, 0, $currentDate['mon'], 1, $currentDate['year'] ) ) );
                if ( $dayNum > $daysInMonth )
                {
                    $dayNum = $daysInMonth;
                }

                $daysDiff = $dayNum - $currentDate['mday'];
                if ( $daysDiff < 0 or
                     ( $daysDiff == 0 and $hoursDiff <= 0 ) )
                {
                    $daysDiff += $daysInMonth;
                }

                $secondsDiff = 3600 * ( $daysDiff * 24 + $hoursDiff )
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;
        }

        $sendDate = time() + $secondsDiff;
        eZDebugSetting::writeDebug( 'kernel-notification', getdate( $sendDate ), "item date"  );
        $item->setAttribute( 'send_date', $sendDate );
        return $sendDate;
    }
}

?>
