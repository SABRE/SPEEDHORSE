<?php
//
// Definition of eZNetSOAPSyncAdvanced class
//
// Created on: <14-Nov-2005 12:53:18 hovik>
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

/*! \file eznetsoapsyncadvanced.php
*/

/*!
  \class eZNetSOAPSyncAdvanced eznetsoapsyncadvanced.php
  \brief The class eZNetSOAPSyncAdvanced does

*/


class eZNetSOAPSyncAdvanced extends eZNetSOAPSync
{

    /*!
     \reimp
    */
    static function functionDefinitionList()
    {
        return array_merge( eZNetSOAPSync::functionDefinitionList() );
    }


    /*!
     \static
     Calculate dependencies of list classes to syncronize. TODO : use more efficient algo than brute force.

     \param list of classes to syncronize.

     \return Ordered list of classes to syncronize
    */
    static function orderClassListByDependencies( $classList )
    {
        $tmpDependencyList = array();

        // build rough depency list
        foreach( $classList as $className )
        {
            $tmpDependencyList[$className] = array();

            // this causes a seg fault: (see issue #001996 in network-project issue tracker)
            //$definition = call_user_func_array( array( $className, "definition" ),
            //                                    array() );

            $definition = call_user_func( array( $className, "definition" ) );

            foreach( $definition['fields'] as $attributeDefinition )
            {
                if ( isset( $attributeDefinition['foreign_class'] ) )
                {
                    $tmpDependencyList[$className][] = $attributeDefinition['foreign_class'];
                }
                if ( isset( $attributeDefinition['foreign_override_class'] ) )
                {
                    $tmpDependencyList[$attributeDefinition['foreign_override_class']][] = $className;
                }
            }
        }

        // cleanup rough list
        foreach( $tmpDependencyList as $className => $classDependencyList )
        {
            $tmpDependencyList[$className] = array_unique( $classDependencyList );
            foreach( $tmpDependencyList[$className] as $key => $value )
            {
                if ( !in_array( $value, array_keys( $tmpDependencyList ) ) )
                {
                    unset( $tmpDependencyList[$className][$key] );
                }
                if ( $className == $value )
                {
                    unset( $tmpDependencyList[$className][$key] );
                }
            }
        }

        $dependencyList = array();
        // Build complete list
        for ( $i = 0 ; $i <= count( $tmpDependencyList ); $i++ )
        {
            $classList2 = array();
            foreach( $tmpDependencyList as $className => $classDependencyList )
            {
                if ( empty( $classDependencyList ) )
                {
                    $classList2[] = $className;
                }
            }

            foreach( $classList2 as $className )
            {
                unset( $tmpDependencyList[$className] );
            }

            foreach( $tmpDependencyList as $className => $classDependencyList )
            {
                foreach( $classDependencyList as $innerKey => $innerValue )
                {
                    if ( in_array( $innerValue, $classList2 ) )
                    {
                        unset( $tmpDependencyList[$className][$innerKey] );
                    }
                }
            }

            $dependencyList = array_merge( $dependencyList, $classList2 );
        }

        // Append list of failed dependencies
        foreach( $tmpDependencyList as $className => $value )
        {
            $dependencyList[] = $className;
        }

        foreach( $dependencyList as $key => $className )
        {
            if ( !in_array( $className, $classList ) )
            {
                unset( $dependencyList[$key] );
            }
        }

        return $dependencyList;
    }
}

?>
