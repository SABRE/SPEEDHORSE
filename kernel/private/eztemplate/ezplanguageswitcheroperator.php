<?php
/**
 * File containing the language_switcher template operator
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

class ezpLanguageSwitcherOperator
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array( 'language_switcher' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'language_switcher' => array( 'destination' => array( 'type' => 'string',
                                                                            'required' => false,
                                                                            'default' => '' ) ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $destination = $namedParameters['destination'];

        switch ( $operatorName )
        {
            case 'language_switcher':
            {
                $ini = eZINI::instance();
                if ( !$ini->hasVariable( 'RegionalSettings', 'LanguageSwitcherClass' ) )
                {
                    return;
                }

                $className = $ini->variable( 'RegionalSettings', 'LanguageSwitcherClass' );
                $operatorValue = call_user_func( array( $className, 'setupTranslationSAList' ), $destination );
            } break;
        }
    }
}

?>
