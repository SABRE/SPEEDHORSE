<?php
//
// Definition of eZDateUtils class
//
// Created on: <05-May-2004 11:51:15 amos>
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
  \class eZDateUtils ezdateutils.php
  \brief The class eZDateUtils does

*/

class eZDateUtils
{
    /*!
     \static
     Return a textual representation of the date according to the RFC 1123 standard.

     rfc1123-date = wkday "," SP date1 SP time SP "GMT"
     date1        = 2DIGIT SP month SP 4DIGIT
                    ; day month year (e.g., 02 Jun 1982)
     time         = 2DIGIT ":" 2DIGIT ":" 2DIGIT
                    ; 00:00:00 - 23:59:59
     wkday        = "Mon" | "Tue" | "Wed"
                  | "Thu" | "Fri" | "Sat" | "Sun"
     month        = "Jan" | "Feb" | "Mar" | "Apr"
                  | "May" | "Jun" | "Jul" | "Aug"
                  | "Sep" | "Oct" | "Nov" | "Dec"
    */
    static function rfc1123Date( $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = time();
        $wday = (int) gmdate( 'w', $timestamp );
        $days = array( 1 => 'Mon', 2 => 'Tue', 3 => 'Wed',
                       4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 0 => 'Sun' );
        $wkday = $days[$wday];
        $month = (int) gmdate( 'n', $timestamp );
        $months = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                         5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                         9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' );

        $mon = $months[$month];
        return gmstrftime( $wkday . ", %d " . $mon . " %Y %H:%M:%S" . " GMT", $timestamp );
    }

    /*!
     \static
     Return a textual representation of the date according to the RFC 850 standard.

     rfc850-date  = weekday "," SP date2 SP time SP "GMT"
     date2        = 2DIGIT "-" month "-" 2DIGIT
                    ; day-month-year (e.g., 02-Jun-82)
     time         = 2DIGIT ":" 2DIGIT ":" 2DIGIT
                    ; 00:00:00 - 23:59:59
     wkday        = "Mon" | "Tue" | "Wed"
                  | "Thu" | "Fri" | "Sat" | "Sun"
     weekday      = "Monday" | "Tuesday" | "Wednesday"
                  | "Thursday" | "Friday" | "Saturday" | "Sunday"
     month        = "Jan" | "Feb" | "Mar" | "Apr"
                  | "May" | "Jun" | "Jul" | "Aug"
                  | "Sep" | "Oct" | "Nov" | "Dec"
    */
    static function rfc850Date( $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = time();
        $wday = (int) gmdate( 'w', $timestamp );
        $days = array( 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday',
                       4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 0 => 'Sunday' );
        $weekday = $days[$wday];
        $month = (int) gmdate( 'n', $timestamp );
        $months = array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                         5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                         9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' );
        $mon = $months[$month];
        return gmstrftime( $weekday . ", %d-" . $mon . "-%Y %H:%M:%S" . " GMT", $timestamp );
    }

    /*!
     \static
     Parses the date \a $dateText which is in text format and returns a timestamp which represents that date.
    */
    static function textToDate( $dateText )
    {
        return strtotime( $dateText );
    }
}

?>
