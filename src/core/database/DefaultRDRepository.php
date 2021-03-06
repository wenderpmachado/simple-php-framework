<?php

/**
 * Basic implementation of Relational Database Repository.
 *
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.7
 */

namespace Core\Database;

use phputil\di\DI;
use Phinx\Migration\MigrationInterface;

abstract class DefaultRDRepository implements DefaultRepository, Relationships, PhinxIntegration {
    private $database = null;
    private $hasMany = null;
    private $hasOne = null;
    const DEFAULT_SUFIX_REPOSITORY = 'Repository';

    public function __construct(Database $database){
        $this->database = $database;
    }

    /*
     * DefaultRepository implementations
     */
    public function getDatabase(){
        return $this->database;
    }

    public function store(&$object){
        $tableName = $this->getTableName();
        $record = $this->objectToRecord($object);
        $fields = $this->fieldsOfRecord($record);
		$correspondingFields = $this->fieldsOfRecord($record, ':');
		$command = "INSERT INTO $tableName ($fields) VALUES ($correspondingFields)";
        $this->database->execute($command, $record);
        $lastInsertId = $this->lastId();
        $object->setId($lastInsertId);
        return $lastInsertId;
    }

    public function update($object){
        $tableName = $this->getTableName();
        $record = $this->objectToRecord($object);
		$fields = $this->fieldsOfRecordToUpdate($record);
		$command = "UPDATE $tableName SET $fields WHERE id = :id";
        return $this->database->run($command, $record);
    }

    public function select($id){
        return $this->database->objectWithId([$this,'recordToObject'], $id, $this->getTableName());
    }

    public function selectAll($limit = 100, $offset = 0){
        return $this->database->allObjects([$this,'recordToObject'], $this->getTableName(), $limit, $offset);
    }

    public function delete($id){
        return $this->database->deleteWithId($id, $this->getTableName());
    }

    public function generateId($idFieldName = 'id'){
        return $this->database->generateId($this->getTableName(), $idFieldName);
    }

    public function lastId($idFieldName = 'id'){
        return $this->database->lastId($this->getTableName(), $idFieldName);
    }

    public function countRows($idFieldName = 'id', $whereClause = '', array $parameters = array()){
        return $this->database->countRows($this->getTableName(), $idFieldName, $whereClause, $parameters);
    }

    /*
     * Relationships implementations
     */
    public function hasOne(&$object, $relationalClass, $foreignKey = false, $localKey = 'id'){
        $relationalRepository = DI::create($this->classNameWithNamespaceToRepositoryName($relationalClass));
        $foreignKey = $this->formatForeignKey($this->getTableName(), $foreignKey);
        $command = 'SELECT '.$relationalRepository->getTableName().'.* FROM '.$relationalRepository->getTableName().' WHERE '.$foreignKey.' = :id';
        $parameters = [
            'id' => $object->getId(),
            'foreignKey' => $foreignKey,
            'tableName' => $relationalRepository->getTableName()
        ];
        $theOne = $relationalRepository->getDatabase()->queryObjects([$relationalRepository,'recordToObject'], $command, $parameters )[0];
        return ($theOne) ? $theOne : false;
    }

    public function hasMany(&$object, $relationalClass, $foreignKey = false, $localKey = 'id'){
        $relationalRepository = DI::create($this->classNameWithNamespaceToRepositoryName($relationalClass));
        $foreignKey = $this->formatForeignKey($this->getTableName(), $foreignKey);
        $command = 'SELECT '.$relationalRepository->getTableName().'.* FROM '.$relationalRepository->getTableName().' WHERE '.$foreignKey.' = :id';
        $parameters = [ 'id' => $object->getId() ];
        $theMany = $relationalRepository->getDatabase()->queryObjects([$relationalRepository,'recordToObject'], $command, $parameters );
        return ($theMany) ? $theMany : false;
    }

    public function belongsTo(&$object, $relationalClass, $foreignKey = false, $localKey = 'id'){
        $relationalRepository = DI::create($this->classNameWithNamespaceToRepositoryName($relationalClass));
        $foreignKey = $this->formatForeignKey($relationalRepository->getTableName(), $foreignKey);
        $command = 'SELECT '.$relationalRepository->getTableName().'.* FROM '.$relationalRepository->getTableName().' JOIN '.$this->getTableName().' ON '.$this->getTableName().'.'.$foreignKey.' = '.$relationalRepository->getTableName().'.id WHERE '.$this->getTableName().'.id = :id';
        $parameters = [ 'id' => $object->getId() ];
		$results = $relationalRepository->getDatabase()->queryObjects([$relationalRepository,'recordToObject'], $command, $parameters );
		$theBelongsTo = false;
		if(isset($results[0]) && $results != null)
        	$theBelongsTo = $results[0];
        return $theBelongsTo;
    }

    public function belongsToMany(&$object, $relationalClass, $foreignKey = false, $localKey = 'id'){
        $relationalRepository = DI::create($this->classNameWithNamespaceToRepositoryName($relationalClass));
        $foreignKey = $this->formatForeignKey($relationalRepository->getTableName(), $foreignKey);
        $command = 'SELECT '.$relationalRepository->getTableName().'.* FROM '.$relationalRepository->getTableName().' JOIN '.$this->getTableName().' ON '.$this->getTableName().'.'.$foreignKey.' = '.$relationalRepository->getTableName().'.id WHERE '.$this->getTableName().'.id = :id';
        $parameters = [ 'id' => $object->getId() ];
        $theBelongsToMany = $relationalRepository->getDatabase()->queryObjects([$relationalRepository,'recordToObject'], $command, $parameters );
        return ($theBelongsToMany) ? $theBelongsToMany : false;
    }

    /**
     * Transforms the array of object fields into string for the insert command.
     *
     * @param array $record
     * @param string $extra
     * @return string
     */
    private function fieldsOfRecord(&$record, $extra = ''){
        $string = '';
		if(isset($record['id']))
			unset($record['id']);
        foreach($record as $key => $value){
            $string .= $extra . $key . ', ';
        }
        return substr($string, 0, -2);
    }

    /**
     * Transform array of object fields into string for update command.
     *
     * @param array $record
     * @return string
     */
    private function fieldsOfRecordToUpdate($record){
        $string = '';
        if(isset($record['id']))
        	unset($record['id']);
        foreach($record as $key => $value){
            $string .= $key . ' = :' . $key . ', ';
        }
        return substr($string, 0, -2);
    }

    /**
     * Places the foreign key in the database pattern, if it is not passed
     *
     * @param string $relationalTableName
     * @param bool $foreignKey
     * @return string
     */
    private function formatForeignKey($relationalTableName, $foreignKey = false){
        return ($foreignKey) ? $foreignKey : $relationalTableName . '_id';
    }

    public static function classNameWithNamespaceToRepositoryName($classNameWithNamespace){
        $relationalExploded = explode('\\', $classNameWithNamespace);
        $relationalClassName = array_pop($relationalExploded);
        return $relationalClassName . self::DEFAULT_SUFIX_REPOSITORY;
    }

    /*
     * Functions that child classes should implement.
     */

    public abstract function recordToObject($record, $blocks);

    public abstract function objectToRecord($object, $blocks);

    public abstract function getTableName();

    public abstract function createTableWithPhinx(MigrationInterface $migration);
}