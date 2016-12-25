<?php

/**
 * Basic CRUD for implementation in a collection.
 *
 * @author	Wender Pinto Machado
 * @email wenderpmachado@gmail.com
 * @version 1.0
 */

namespace App\Database;

interface DefaultCollection extends Collection {
    public function __construct(Database $database);
    public function create(&$object);
    public function update($object);
    public function select($id);
    public function selectAll($limit, $offset);
    public function delete($id);
    public function recordToObject($record, $blocks);
    public function objectToRecord($object, $blocks);
}