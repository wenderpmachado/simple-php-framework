<?php

/**
 * Class to connect and interaction with the database.
 *
 * @author	Wender Pinto Machado
 * @email wenderpmachado@gmail.com
 * @version 1.0
 */

namespace App\Database;

use phputil\PDOBuilder;
use phputil\PDOWrapper;

class Database {
    private $pdoWrapper = null;

    public function getPdoWrapper(){
        return $this->pdoWrapper;
    }

    public function __construct(PDOWrapper $pdoWrapper){
        $this->pdoWrapper = $pdoWrapper;
    }

    public static function buildPDO(){
        $dsn = getenv('DB_CONNECTION').':host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE').';';
        return PDOBuilder::with()->dsn($dsn)
                                 ->username(getenv('DB_USERNAME'))
                                 ->password(getenv('DB_PASSWORD'))
                                 ->modeException()
                                 ->persistent()
                                 ->mySqlUTF8()
                                 ->build();
    }

    /**
     * Function for Database directly use the functions of PDOWrapper class.
     *
     * @example
     *      $database = new Database();
     *      $database->deleteWithId(...); // function of PDOWrapper
     */
    public function __call($method, $params) {
        return call_user_func_array(array($this->pdoWrapper, $method), $params);
    }
}