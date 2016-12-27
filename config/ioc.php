<?php

use \phputil\di\DI;
use \App\Database\Database;

// Main configuration

DI::config(DI::let('PDO')->call(function(){
    return Database::buildPDO();
})->shared());

DI::config(DI::let('PDOWrapper')->call(function(){
    return Database::buildPDOWrapper();
})->shared());

DI::config(DI::let('Database')->call(function(){
    return new Database(DI::create('PDOWrapper'));
})->shared());

// Dependency Injection Configuration

DI::config(DI::let('AddressRepository')->create('\App\Address\AddressRDRepository')->shared());
