<?php

/**
 * This file was based on the library thiagodp/php-util, from the author Thiago Delgado Pinto,
 * can be accessed by linklink: https://github.com/thiagodp/php-util
 *
 * @author	Wender Pinto Machado
 * @email wenderpmachado@gmail.com
 * @version	1.0
 */

namespace Core\Helper;

class ClassMaker {
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

        return $this->createDirAndFile($className, $className, $modelClass, $overwrite);
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
        $returnInterface = $this->createDirAndFile($className, $classNameInterface, $Repository['interface'], $overwrite);
        $returnRD = $this->createDirAndFile($className, $classNameRD, $Repository['bdr'], $overwrite);
        $success = ($returnInterface && $returnRD) ? true : false;
        if($success){
            $this->insertDILineIntoIocConfigFile($className, IocType::REPOSITORY);
			$this->insertDILineIntoSlimIocConfigFile($className, IocType::REPOSITORY);
            ExecUtil::composerDumpAutoload();
            ExecUtil::createMigration($className);
        }
        return $success;
    }

    /**
     * Create the Repository Interface file.
     *
     * @param string $className
     * @param array $RepositoryInterfaceUses
     * @return string
     */
    private function makeRepositoryInterface($className, $RepositoryInterfaceUses = ['Core\Database\DefaultRepository']){
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
    private function makeRepositoryInRD($className, $parameters, $RepositoryInRDUses = ['Core\Database\DefaultRDRepository', 'Phinx\Migration\MigrationInterface']){
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
        $RepositoryInRD .= PHP_EOL;

        $RepositoryInRD .= "\t".'public function createTableWithPhinx(MigrationInterface $migration){';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t\t".'$table = $migration->table($this->getTableName());';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t\t".'return ' . $this->fieldsToMigration($fields);
        $RepositoryInRD .= "\t\t\t\t\t ".'->create();';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= "\t".'}';
        $RepositoryInRD .= PHP_EOL;
        $RepositoryInRD .= '}';

        return $RepositoryInRD;
    }

    public function makeController($className, $parameters, $overwrite = true, array $uses = ['\Psr\Http\Message\ServerRequestInterface as Request', '\Psr\Http\Message\ResponseInterface as Response', 'Core\Database\DefaultRestController']){
		$className = ucfirst($className);
		$realClassName = $className . 'Controller';
		$objectName = '$'.lcfirst($className);
		$fields = $this->parametersToFields($parameters);
		$usesString = $this->usesToString($uses);
		$namespace = $this->makeNamespace($className);

		$controllerClass  = $this->makeHeadClass($namespace, $usesString);
		$controllerClass .= 'class '.$realClassName.' extends DefaultRestController {';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t".'public function __construct('.$className.'Repository $repository) {';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t\t".'parent::__construct($repository);';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t}";
		$controllerClass .= PHP_EOL;
		$controllerClass .= PHP_EOL;

		$controllerClass .= "\t".'public function create(Request $request, Response $response, $args) {';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t\t".'return new \NotImplementedException();';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t}";
		$controllerClass .= PHP_EOL;
		$controllerClass .= PHP_EOL;

		$controllerClass .= "\t".'public function requestToObject($args) {';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t\t".'$args = $this->sanitizeArgs($args);';
		$controllerClass .= PHP_EOL;
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t\t".$objectName.' = new '.$className.'();';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t\t".'if(isset($args[\'id\']) && $args[\'id\'] != null)';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t\t\t".$objectName.'->setId($args[\'id\']);';
		$controllerClass .= PHP_EOL;
		$controllerClass .= $this->fieldsToObject($fields, $objectName);
		$controllerClass .= "\t\t".'return '.$objectName.';';
		$controllerClass .= PHP_EOL;
		$controllerClass .= "\t}";
		$controllerClass .= PHP_EOL;
		$controllerClass .= '}';

		$success = $this->createDirAndFile($className, $realClassName, $controllerClass, $overwrite);
		if($success){
			$this->insertDILineIntoIocConfigFile($className, IocType::CONTROLLER);
			$this->insertDILineIntoSlimIocConfigFile($className, IocType::CONTROLLER);
			ExecUtil::composerDumpAutoload();
		}
    }

    public function makeRoutes($className, $overwrite = true){
		$namespace	 = $this->makeNamespace($className);
    	$routesFile  = $this->makeHeadClass($namespace);
    	$groupName = strtolower($className);
		$realFileName = ucfirst($className) . 'Routes';
    	$controllerName = ucfirst($className) . 'Controller';

    	$routesFile .= 'global $app;';
		$routesFile .= PHP_EOL;
		$routesFile .= PHP_EOL;
		$routesFile .= '$app->group(\'/'.$groupName.'\', function() use ($app) {';
		$routesFile .= PHP_EOL;
		$routesFile .= "\t".'$app->get(\'[/]\', \''.$controllerName.':index\');';
		$routesFile .= PHP_EOL;
		$routesFile .= "\t".'$app->get(\'/{id:[0-9]+}[/]\', \''.$controllerName.':edit\');';
		$routesFile .= PHP_EOL;
		$routesFile .= "\t".'$app->put(\'/{id:[0-9]+}[/]\', \''.$controllerName.':update\');';
		$routesFile .= PHP_EOL;
		$routesFile .= "\t".'$app->delete(\'/{id:[0-9]+}[/]\', \''.$controllerName.':delete\');';
		$routesFile .= PHP_EOL;
		$routesFile .= "\t".'$app->get(\'/create[/]\', \''.$controllerName.':create\');';
		$routesFile .= PHP_EOL;
		$routesFile .= "\t".'$app->post(\'/create[/]\', \''.$controllerName.':store\');';
		$routesFile .= PHP_EOL;
		$routesFile .= '});';
		$routesFile .= PHP_EOL;

		$success = $this->createDirAndFile($className, ucfirst($realFileName), $routesFile, $overwrite);
		if($success){
			$this->insertRequireLineIntoRoutesConfigFile($className);
			ExecUtil::composerDumpAutoload();
		}
	}

    // UTIL

    private function createDirAndFile($dirName, $fileName, $fileContent, $overwrite = true){
        $folder = getenv('BASE_DIR') . getenv('APP_DIR') . mb_strtolower($dirName);
        $filePath = getenv('BASE_DIR') . getenv('APP_DIR') . mb_strtolower($dirName) . '/' . $fileName . '.php';

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
    
    private function makeHeadClass($namespace, $usesString = ''){
        $head  = '<?php'.PHP_EOL;
        $head .= PHP_EOL;
        $head .= 'namespace '.$namespace.';'.PHP_EOL;
        if($usesString != '')
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

    private function fieldsToString(array $fieldArray, $nullify = false){
        $string = '';
        foreach ($fieldArray as $field => $type) {
            $string .=	PHP_EOL . "\tprivate \$$field" . $this->initialValueFor( $type, $nullify ) . ';';
        }
        return $string;
    }

    private function fieldsToMigration(array $fieldArray, $varTableName = '$table', $created = true, $updated = true){
		$rows = $varTableName;
		if(isset($fieldArray['id']))
			unset($fieldArray['id']);

		$firstElement = array_keys($fieldArray)[0];
		foreach($fieldArray as $field => $type){
			if($firstElement != $field)
				$rows .= "\t\t\t\t\t ";
			$rows .= '->addColumn(\'' . $field . '\', \'' . $this->fieldTypeToColumnType($type) . '\')' . PHP_EOL;
        }

        if($created)
            $rows .= "\t\t\t\t\t ".'->addColumn(\'created\', \'datetime\', array(\'default\' => \'CURRENT_TIMESTAMP\'))' . PHP_EOL;

        if($updated)
            $rows .= "\t\t\t\t\t ".'->addColumn(\'updated\', \'datetime\', array(\'null\' => true))' . PHP_EOL;

        return $rows;
    }

    private function fieldsToObject(array $fieldArray, $varName, $withId = false){
		if(!$withId && isset($fieldArray['id']))
			unset($fieldArray['id']);

		$rows = '';
		foreach($fieldArray as $field => $type){
			$rows .= "\t\t".$varName.'->set' . ucfirst($field) . '($args[\'' . $field . '\']);' . PHP_EOL;
        }

        return $rows;
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

    private function fieldTypeToColumnType($type){
        if ( 'string'  === $type ) { return 'string'; }
        if ( 'integer' === $type || 'int'   === $type ) { return 'integer'; }
        if ( 'double'  === $type || 'float' === $type ) { return 'float'; }
        if ( 'boolean' === $type || 'bool'  === $type ) { return 'boolean'; }
        return $type;
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

    private function insertDILineIntoIocConfigFile($className, $iocType = IocType::REPOSITORY){
		$className = ucfirst($className);
        $namespace = $this->makeNamespace($className);
        $insert = '';
        switch ($iocType) {
			case IocType::REPOSITORY:
				$insert = 'DI::config(DI::let(\''.$className.'Repository\')->create(\'\\'.$namespace.'\\'.$className.'RDRepository\')->shared());';
				break;
			case IocType::CONTROLLER:
				$insert  = 'DI::config(DI::let(\''.$className.'Controller\')->call(function(){';
				$insert .= 'return new '.$namespace.'\\'.$className.'Controller(DI::create(\''.$className.'Repository\'));';
				$insert .= '})->shared());';
				break;
		}

        $iocConfigFileLines  = explode(PHP_EOL, file_get_contents(getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("IOC_CONFIG_FILE")));
        if(strlen(end($iocConfigFileLines)) == 0)
            $iocConfigFileLines[count($iocConfigFileLines)-1] = $insert;
        else
            $iocConfigFileLines[] = $insert;
        return file_put_contents(getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("IOC_CONFIG_FILE"), implode(PHP_EOL, $iocConfigFileLines));
    }

	private function insertRequireLineIntoRoutesConfigFile($className){
		$fileLocation = getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("ROUTES_FILE");
		$insert = 'require getenv(\'BASE_DIR\') . getenv(\'APP_DIR\') . \''.strtolower($className).'\' . \'/\' . \''.ucfirst($className).'Routes.php\';';

		$routesConfigFileLines  = explode(PHP_EOL, file_get_contents($fileLocation));
		if(strlen(end($routesConfigFileLines)) == 0)
			$routesConfigFileLines[count($routesConfigFileLines)-1] = $insert;
		else
			$routesConfigFileLines[] = $insert;
		return file_put_contents($fileLocation, implode(PHP_EOL, $routesConfigFileLines));
	}

	private function insertDILineIntoSlimIocConfigFile($className, $iocType = IocType::REPOSITORY){
		$fileLocation = getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("SLIM_IOC_CONFIG_FILE");
		$className = ucfirst($className);
		$insert = '$container[\''.$className.ucfirst($iocType).'\'] = function ($container) { return DI::create(\''.$className.ucfirst($iocType).'\'); };';

		$routesConfigFileLines  = explode(PHP_EOL, file_get_contents($fileLocation));
		if(strlen(end($routesConfigFileLines)) == 0)
			$routesConfigFileLines[count($routesConfigFileLines)-1] = $insert;
		else
			$routesConfigFileLines[] = $insert;
		return file_put_contents($fileLocation, implode(PHP_EOL, $routesConfigFileLines));
	}

    private function insertSlimDILineIntoIocConfigFile($className){
        $namespace = $this->makeNamespace($className);
        $insert = '$container[\''.$className.'Repository\'] = function ($container) { return DI::create(\''.$className.'Repository\'); };';
        $iocConfigFileLines  = explode(PHP_EOL, file_get_contents(getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("SLIM_IOC_CONFIG_FILE")));
        if(strlen(end($iocConfigFileLines)) == 0)
            $iocConfigFileLines[count($iocConfigFileLines)-1] = $insert;
        else
            $iocConfigFileLines[] = $insert;
        return file_put_contents(getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("IOC_CONFIG_FILE"), implode(PHP_EOL, $iocConfigFileLines));
    }
}