<?php
//
// Definition of eZModuleFunctionInfo class
//
// Created on: <06-Oct-2002 16:27:36 amos>
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
  \class eZModuleFunctionInfo ezmodulefunctioninfo.php
  \brief The class eZModuleFunctionInfo does

*/

class eZModuleFunctionInfo
{
    const ERROR_NO_CLASS = 5;
    const ERROR_NO_CLASS_METHOD = 6;
    const ERROR_CLASS_INSTANTIATE_FAILED = 7;
    const ERROR_MISSING_PARAMETER = 8;

    /*!
     Constructor
    */
    function eZModuleFunctionInfo( $moduleName )
    {
        $this->ModuleName = $moduleName;
        $this->IsValid = false;
        $this->FunctionList = array();
    }

    function isValid()
    {
        return $this->IsValid;
    }

    function loadDefinition()
    {
        $definitionFile = null;
        $pathList = eZModule::globalPathList();
        if ( $pathList )
        {
            foreach ( $pathList as $path )
            {
                $definitionFile = $path . '/' . $this->ModuleName . '/function_definition.php';
                if ( file_exists( $definitionFile ) )
                    break;
                $definitionFile = null;
            }
        }
        if ( $definitionFile === null )
        {
            eZDebug::writeError( 'Missing function definition file for module: ' . $this->ModuleName, __METHOD__ );
            return false;
        }
        unset( $FunctionList );
        include( $definitionFile );
        if ( !isset( $FunctionList ) )
        {
            eZDebug::writeError( 'Missing function definition list for module: ' . $this->ModuleName, __METHOD__ );
            return false;
        }
        $this->FunctionList = $FunctionList;
        $this->IsValid = true;
        return true;
    }

    /*!
      Check if a parameter for a function is an array

      \param functionName function name
      \param parameterName parameter name

      \return true if parameter is supposed to be array
     */
    function isParameterArray( $functionName, $parameterName )
    {
        if ( isset( $this->FunctionList[$functionName]['parameters'] ) )
        {
            $parameterList = $this->FunctionList[$functionName]['parameters'];
            foreach ( $parameterList as $parameter )
            {
                if ( $parameter['name'] == $parameterName )
                {
                    if ( $parameter['type'] == 'array' )
                    {
                        return true;
                    }
                    return false;
                }
            }
        }
        return false;
    }

    /*!
     Pre execute, used by template compilation to check as much as possible before runtime.

     \param functionName function name

     \return function definition, false if fails.
    */
    function preExecute( $functionName )
    {
        /* Code copied from this->execute() */
        $moduleName = $this->ModuleName;
        if ( !isset( $this->FunctionList[$functionName] ) )
        {
            eZDebug::writeError( "No such function '$functionName' in module '$moduleName'", __METHOD__ );
            return false;
        }
        $functionDefinition = $this->FunctionList[$functionName];
        if ( !isset( $functionName['call_method'] ) )
        {
            eZDebug::writeError( "No call method defined for function '$functionName' in module '$moduleName'", __METHOD__ );
            return false;
        }
        if ( !isset( $functionName['parameters'] ) )
        {
            eZDebug::writeError( "No parameters defined for function '$functionName' in module '$moduleName'", __METHOD__ );
            return false;
        }
        $callMethod = $functionDefinition['call_method'];
        if ( isset( $callMethod['class'] ) &&
             isset( $callMethod['method'] ) )
        {
            if ( !class_exists( $callMethod['class'] ) )
            {
                return false;
            }
            $classObject = $this->objectForClass( $callMethod['class'] );
            if ( $classObject === null )
            {
                return false;
            }
            if ( !method_exists( $classObject, $callMethod['method'] ) )
            {
                return false;
            }

            return $functionDefinition;
        }
        return false;
    }

    function execute( $functionName, $functionParameters )
    {
        $moduleName = $this->ModuleName;
        if ( !isset( $this->FunctionList[$functionName] ) )
        {
            eZDebug::writeError( "No such function '$functionName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        $functionDefinition = $this->FunctionList[$functionName];
        if ( !isset( $functionName['call_method'] ) )
        {
            eZDebug::writeError( "No call method defined for function '$functionName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        if ( !isset( $functionName['parameters'] ) )
        {
            eZDebug::writeError( "No parameters defined for function '$functionName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        $callMethod = $functionDefinition['call_method'];
        if ( isset( $callMethod['class'] ) &&
             isset( $callMethod['method'] ) )
        {
            $resultArray = $this->executeClassMethod( $callMethod['class'], $callMethod['method'],
                                                      $functionDefinition['parameters'], $functionParameters );
        }
        else
        {
            eZDebug::writeError( "No valid call methods found for function '$functionName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        if ( !is_array( $resultArray ) )
        {
            eZDebug::writeError( "Function '$functionName' in module '$moduleName' did not return a result array", __METHOD__ );
            return null;
        }
        if ( isset( $resultArray['internal_error'] ) )
        {
            switch ( $resultArray['internal_error'] )
            {
                case eZModuleFunctionInfo::ERROR_NO_CLASS:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "No class '$className' available for function '$functionName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
                case eZModuleFunctionInfo::ERROR_NO_CLASS_METHOD:
                {
                    $className = $resultArray['internal_error_class_name'];
                    $classMethodName = $resultArray['internal_error_class_method_name'];
                    eZDebug::writeError( "No method '$classMethodName' in class '$className' available for function '$functionName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
                case eZModuleFunctionInfo::ERROR_CLASS_INSTANTIATE_FAILED:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "Failed instantiating class '$className' which is needed for function '$functionName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
                case eZModuleFunctionInfo::ERROR_MISSING_PARAMETER:
                {
                    $parameterName = $resultArray['internal_error_parameter_name'];
                    eZDebug::writeError( "Missing parameter '$parameterName' for function '$functionName' in module '$moduleName'",
                                         __METHOD__ . " $moduleName::$functionName" );
                    return null;
                } break;
                default:
                {
                    $internalError = $resultArray['internal_error'];
                    eZDebug::writeError( "Unknown internal error '$internalError' for function '$functionName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
            }
            return null;
        }
        else if ( isset( $resultArray['error'] ) )
        {
        }
        else if ( isset( $resultArray['result'] ) )
        {
            return $resultArray['result'];
        }
        else
        {
            eZDebug::writeError( "Function '$functionName' in module '$moduleName' did not return a result value", __METHOD__ );
        }
        return null;
    }

    function objectForClass( $className )
    {
        if ( !isset( $GLOBALS['eZModuleFunctionClassObjectList'] ) )
        {
            $GLOBALS['eZModuleFunctionClassObjectList'] = array();
        }
        if ( isset( $GLOBALS['eZModuleFunctionClassObjectList'][$className] ) )
        {
            return $GLOBALS['eZModuleFunctionClassObjectList'][$className];
        }
        return $GLOBALS['eZModuleFunctionClassObjectList'][$className] = new $className();
    }

    function executeClassMethod( $className, $methodName,
                                 $functionParameterDefinitions, $functionParameters )
    {
        if ( !class_exists( $className ) )
        {
            return array( 'internal_error' => eZModuleFunctionInfo::ERROR_NO_CLASS,
                          'internal_error_class_name' => $className );
        }
        $classObject = $this->objectForClass( $className );
        if ( $classObject === null )
        {
            return array( 'internal_error' => eZModuleFunctionInfo::ERROR_CLASS_INSTANTIATE_FAILED,
                          'internal_error_class_name' => $className );
        }
        if ( !method_exists( $classObject, $methodName ) )
        {
            return array( 'internal_error' => eZModuleFunctionInfo::ERROR_NO_CLASS_METHOD,
                          'internal_error_class_name' => $className,
                          'internal_error_class_method_name' => $methodName );
        }
        $parameterArray = array();
        foreach ( $functionParameterDefinitions as $functionParameterDefinition )
        {
            $parameterName = $functionParameterDefinition['name'];
            if ( isset( $functionParameters[$parameterName] ) )
            {
                // Do type checking
                $parameterArray[] = $functionParameters[$parameterName];
            }
            else
            {
                if ( $functionParameterDefinition['required'] )
                {
                    return array( 'internal_error' => eZModuleFunctionInfo::ERROR_MISSING_PARAMETER,
                                  'internal_error_parameter_name' => $parameterName );
                }
                else if ( isset( $functionParameterDefinition['default'] ) )
                {
                    $parameterArray[] = $functionParameterDefinition['default'];
                }
                else
                {
                    $parameterArray[] = null;
                }
            }
        }

        return call_user_func_array( array( $classObject, $methodName ), $parameterArray );
    }

    /*!
     \deprecated use call_user_func_array() instead
    */
    function callClassMethod( $methodName, $classObject, $parameterArray )
    {
/*        echo "*********** fetching START **************** <br>";
        var_dump( $methodName );
        var_dump( $classObject );
        var_dump( $parameterArray );
        echo "*********** fetching END ******************<br><br><br>";*/

        return call_user_func_array( array( $classObject, $methodName ), $parameterArray );
    }


    /// \privatesection
    public $ModuleName;
    public $FunctionList;
    public $IsValid;
}

?>
