<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 1.0
 */

use \phputil\di\DI;
use \Core\Database\Database;

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

DI::config(DI::let('CompanyRepository')->create('\App\Company\CompanyRDRepository')->shared());
DI::config(DI::let('CompanyController')->call(function(){return new App\Company\CompanyController(DI::create('CompanyRepository'));})->shared());