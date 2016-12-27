<?php

/**
 * This file was based on the library thiagodp/php-util, from the author Thiago Delgado Pinto,
 * can be accessed by linklink: https://github.com/thiagodp/php-util
 *
 * @author	Wender Pinto Machado
 * @email wenderpmachado@gmail.com
 * @version	0.6
 */

class ClassMaker {
    const MAIN_FOLDER = __DIR__;

    /**
     * Create the model class.
     *
     * @param string $className The name of the class
     * @param array $parameters Array when key is the name of field and the value is your type
     * @param bool $overwrite Overwrite (or not) the file
     * @return bool
     *
     * @example
     *  <?php
     *
     *  namespace App\User;
     *
     *  use phputil\traits\GetterSetterWithBuilder;
     *
     *  class User {
     *      use GetterSetterWithBuilder;
     *
     *      private $id = 0;
     *      private $name = '';
     *      private $address = null;
     *  }
     */
    public function makeModel($className, $parameters, $overwrite = true){
        $namespace = $this->makeNamespace($className);
        $modelUses = ['phputil\traits\GetterSetterWithBuilder'];
        $fields = $this->parametersToFields($parameters);
        $fieldsString = $this->fieldsToString($fields);
        $usesString = $this->usesToString($modelUses);

        $modelClass  = $this->makeHeadClass($namespace, $usesString);
        $modelClass .= 'class '.$className.' {'.PHP_EOL;
        $modelClass .= "\t".'use GetterSetterWithBuilder;'.PHP_EOL;
        $modelClass .= $fieldsString . PHP_EOL . "}";

        return $this->createDirAndClass($className, $className, $modelClass, $overwrite);
    }

    /**
     * Create the Repository Interface and the Repository in Relational Database files.
     *
     * @param string $className
     * @param array $parameters
     * @param bool $overwrite
     * @return bool
     */
    public function makeRepository($className, $parameters, $overwrite = true){
        $Repository['interface'] = $this->makeRepositoryInterface($className);
        $Repository['bdr'] = $this->makeRepositoryInRD($className, $parameters);

        $classNameInterface = $className.'Repository';
        $classNameRD = $className.'RDRepository';
        $returnInterface = $this->createDirAndClass($className, $classNameInterface, $Repository['interface'], $overwrite);
        $returnRD = $this->createDirAndClass($className, $classNameRD, $Repository['bdr'], $overwrite);
        $success = ($returnInterface && $returnRD) ? true : false;
        if($success)
            return $this->insertDILineIntoIocConfigFile($className);
    }

    /**
     * Create the Repository Interface file.
     *
     * @param string $className
     * @param array $RepositoryInterfaceUses
     * @return string
     */
    private function makeRepositoryInterface($className, $RepositoryInterfaceUses = ['App\Database\DefaultRepository']){
        $namespace = $this->makeNamespace($className);
        $usesString = $this->usesToString($RepositoryInterfaceUses);

        $RepositoryInterface  = $this->makeHeadClass($namespace, $usesString);
        $RepositoryInterface .= 'interface '.$className.'Repository extends DefaultRepository {'.PHP_EOL;
        $RepositoryInterface .= PHP_EOL."}";
        return $RepositoryInterface;
    }

    /**
     * Create the Repository in Relational Database file.
     *
     * @param string $className
     * @param array $parameters
     * @param array $RepositoryInRDUses
     * @return string
     */
    private function makeRepositoryInRD($className, $parameters, $RepositoryInRDUses = ['App\Database\DefaultRDRepository']){
        $usesString = $this->usesToString($RepositoryInRDUses);
        $fields = $this->parametersToFields($parameters);
        $namespace = $this->makeNamespace($className);
        $tableName = lcfirst($className);
        $objectName = '$'.lcfirst($className);
        $objPopulated = $this->populateClassUsingSetters($objectName, $fields);
        $arrayPopulated = $this->populateArrayUsingGetters($objectName, $fields);

        $RepositoryInRD  = $this->makeHeadClass($namespace, $usesString);
        $RepositoryInRD .= 'class '.$className.'RDRepository extends DefaultRDRepository implements '.$className.'Repository {';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t".'public function recordToObject($record, $blocks = []){';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t\t".$objectName.' = new '.$className.'();';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= $objPopulated;
        $RepositoryInRD .= "\t\t".'return '.$objectName .';';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t}";
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= PHP_EOL;

        $RepositoryInRD .= "\t".'public function objectToRecord('.$objectName.', $blocks = []){';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t\t".'return [';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= $arrayPopulated;
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t\t".'];';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t".'}';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= PHP_EOL;

        $RepositoryInRD .= "\t".'public function getTableName(){';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t\treturn '".$tableName."';";
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t".'}';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= '}';

        return $RepositoryInRD;
    }

    public function makeController(){
        // NOT IMPLEMENTED YET
    }

    // UTIL

    private function createDirAndClass($dirName, $className, $fileContent, $overwrite = true){
        $folder = ClassMaker::MAIN_FOLDER . '/' . mb_strtolower($dirName);
        $filePath = ClassMaker::MAIN_FOLDER . '/' . mb_strtolower($dirName) . '/' . $className . '.php';

        if(!is_dir($folder)){
            mkdir($folder);
        }

        if(file_exists($filePath) && !$overwrite){
           return false;
        }else{
            $bytes = file_put_contents($filePath, $fileContent);
            return ($bytes) ? true : false ;
        }
    }
    
    private function makeHeadClass($namespace, $usesString){
        $head  = '<?php'.PHP_EOL;
        $head .= PHP_EOL;
        $head .= 'namespace '.$namespace.';'.PHP_EOL;
        $head .= $usesString . PHP_EOL;
        $head .= PHP_EOL;
        return $head;
    }

    private function makeNamespace($className, $namespaceStruct = 'App\\'){
        return $namespaceStruct.$className;
    }

    private function usesToString($useArray){
        $string = '';
        foreach($useArray as $use){
            $string .= PHP_EOL . 'use ' . $use . ';';
        }
        return $string;
    }

    private function parametersToFields($parameters){
        $fields = array();
        foreach ( $parameters as $key => $value ) {
            $fieldName = trim( $key );
            $fieldType = trim( $value );
            // Ignore options
            if ( isset( $options[ $fieldName ] ) ) { continue; }
            // Add to fields. The value is the field type.
            $fields[ $fieldName ] = ( '' === $fieldType ) ? 'string' : $fieldType;
        }
        return $fields;
    }

    private function fieldsToString($fieldArray, $nullify = false){
        $string = '';
        foreach ($fieldArray as $field => $type) {
            $string .=	PHP_EOL . "\tprivate \$$field" . $this->initialValueFor( $type, $nullify ) . ';';
        }
        return $string;
    }

    private function initialValueFor( $type, $nullify = false ) {
        if ( 'string'  === $type ) { return ' = \'\''; }
        if ( 'integer' === $type || 'int'   === $type ) { return ' = 0'; }
        if ( 'double'  === $type || 'float' === $type ) { return ' = 0.0'; }
        if ( 'boolean' === $type || 'bool'  === $type ) { return ' = false'; }
        if ( 'array'   === $type ) { return ' = array()'; }
        if ( $nullify ) { return ' = null'; }
        return ''; // None
    }

    private function functionName( $field ) {
        return mb_strtoupper( mb_substr( $field, 0, 1 ) )
        . mb_substr( $field, 1 );
    }

    private function getterNameFor( $field, $getter ) {
        return $getter . $this->functionName( $field );
    }

    private function setterNameFor( $field, $setter ) {
        return $setter . $this->functionName( $field );
    }

    private function populateClassUsingSetters($objectName, $fields, $arrayVarName = '$record'){
        $string = '';
        foreach ($fields as $field => $type) {
            $setterName = $this->setterNameFor($field, 'set');
            $string .= "\t\t$objectName->$setterName(${arrayVarName}['$field']);" . PHP_EOL;
        }
        return $string;
    }

    private function populateArrayUsingGetters($objectName, $fields){
        $string = '';
        foreach ($fields as $field => $type) {
            $getterName = $this->getterNameFor($field, 'get');
            $string .= "\t\t\t'$field' => $objectName->$getterName()," . PHP_EOL;
        }
        return substr($string, 0, -2);
    }

    private function insertDILineIntoIocConfigFile($className){
        $namespace = $this->makeNamespace($className);
        $insert = 'DI::config(DI::let(\''.$className.'Repository\')->create(\'\\'.$namespace.'\\'.$className.'RDRepository\')->shared());';
        $iocConfigFileLines  = explode(PHP_EOL, file_get_contents(IOC_CONFIG_FILE));
        if(strlen(end($iocConfigFileLines)) == 0)
            $iocConfigFileLines[count($iocConfigFileLines)-1] = $insert;
        else
            $iocConfigFileLines[] = $insert;
        return file_put_contents(IOC_CONFIG_FILE , implode(PHP_EOL, $iocConfigFileLines));
    }
}