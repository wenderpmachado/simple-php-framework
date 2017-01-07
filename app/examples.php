<?php
//use \App\User\User;
use \App\Address\Address;

/**
 * Criar classes a partir do ClassMaker
 */
//use Core\helper\ClassMaker;
//
//$classMaker = new ClassMaker();
//$className = 'User';
//$parameters = [
//    'id' => 'integer',
//    'name' => 'string',
//    'email' => 'string'
//];
//
//
//echo $classMaker->makeModel($className, $parameters);
//echo $classMaker->makeRepository($className, $parameters);

/**
 * Instanciar um Repository a partir do DI (Dependency Injection)
 */
use \phputil\di\DI;
//
//$userRepository = DI::create('UserRepository');
////
//$user = $userRepository->select(1);
//var_dump($userRepository->addresses($user));

//
//var_dump($userRepository->create($user));
//var_dump($userRepository->select(1));

//$addressRepository = DI::create('AddressRepository');
//$address = new Address();
//$address->setRoad('Fazenda da Laje');
//$addressRepository->create($address);


$addressRepository = DI::create('AddressRepository');

$address = $addressRepository->select(1);
var_dump($address);
var_dump($addressRepository->user($address));






//127.0.0.1:46841 [500]: / - Uncaught PDOException: SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.* FROM 'user' JOIN 'address' ON 'address'.'user_id' = 'user'.id WHERE 'address'' at line 1 in /var/www/html/0_open-source/simples-php-framework/vendor/phputil/pdowrapper/lib/PDOWrapper.php:300
