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
     * Create the Collection Interface and the Collection in Relational Database files.
     *
     * @param string $className
     * @param array $parameters
     * @param bool $overwrite
     * @return bool
     */
    public function makeCollection($className, $parameters, $overwrite = true){
        $collection['interface'] = $this->makeCollectionInterface($className);
        $collection['bdr'] = $this->makeCollectionInRD($className, $parameters);

        $classNameInterface = 'ColecaoDe'.$className;
        $classNameRD = 'ColecaoDe'.$className.'EmBDR';
        $returnInterface = $this->createDirAndClass($className, $classNameInterface, $collection['interface'], $overwrite);
        $returnRD = $this->createDirAndClass($className, $classNameRD, $collection['bdr'], $overwrite);
        return ($returnInterface && $returnRD) ? true : false;
    }

    /**
     * Create the Collection Interface file.
     *
     * @param string $className
     * @param array $collectionInterfaceUses
     * @return string
     */
    private function makeCollectionInterface($className, $collectionInterfaceUses = ['App\BancoDados\ColecaoPadrao']){
        $namespace = $this->makeNamespace($className);
        $usesString = $this->usesToString($collectionInterfaceUses);

        $collectionInterface  = $this->makeHeadClass($namespace, $usesString);
        $collectionInterface .= 'interface ColecaoDe'.$className.' extends ColecaoPadrao {'.PHP_EOL;
        $collectionInterface .= PHP_EOL."}";
        return $collectionInterface;
    }

    /**
     * Create the Collection in Relational Database file.
     *
     * @param string $className
     * @param array $parameters
     * @param array $collectionInRDUses
     * @return string
     */
    private function makeCollectionInRD($className, $parameters, $collectionInRDUses = ['App\BancoDados\ColecaoEmBDRPadrao']){
        $usesString = $this->usesToString($collectionInRDUses);
        $fields = $this->parametersToFields($parameters);
        $namespace = $this->makeNamespace($className);
        $tableName = lcfirst($className);
        $objectName = '$'.lcfirst($className);
        $objPopulated = $this->populateClassUsingSetters($objectName, $fields);
        $arrayPopulated = $this->populateArrayUsingGetters($objectName, $fields);

        $collectionInRD  = $this->makeHeadClass($namespace, $usesString);
        $collectionInRD .= 'class ColecaoDe'.$className.'EmBDR extends ColecaoEmBDRPadrao implements ColecaoDe'.$className.' {';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t".'public function recordToObject($record, $blocks = []){';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t\t".$objectName.' = new '.$className.'();';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= $objPopulated;
        $collectionInRD .= "\t\t".'return '.$objectName .';';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t}";
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= PHP_EOL;

        $collectionInRD .= "\t".'public function objectToRecord('.$objectName.', $blocks = []){';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t\t".'return [';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= $arrayPopulated;
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t\t".'];';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t".'}';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= PHP_EOL;

        $collectionInRD .= "\t".'public function getTableName(){';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t\treturn '".$tableName."';";
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= "\t".'}';
        $collectionInRD .= PHP_EOL;
        $collectionInRD .= '}';

        return $collectionInRD;
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
}

// EXECUTIONd



$classMaker = new ClassMaker();
$parameters = [
    'id' => 'integer',
    'road' => 'string'
];
$className = 'Address';
echo $classMaker->makeModel($className, $parameters);
echo $classMaker->makeCollection($className, $parameters);