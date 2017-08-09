<?php
use \phputil\di\DI;
//use \App\User\User;
//use \App\Address\Address;
use \App\Company;

/**
 * Criar classes a partir do ClassMaker
 */
//$companyController = DI::create('CompanyController');
//dd($companyController);
use \Core\Helper\ClassMaker;

$classMaker = new ClassMaker();
$className = 'Company';
$parameters = [
    'id' => 'integer',
    'name' => 'integer'
];

$classMaker->makeModel($className, $parameters);
$classMaker->makeRepository($className, $parameters);
$classMaker->makeController($className, $parameters);
$classMaker->makeRoutes($className);




/**
 * Instanciar um Repository a partir do DI (Dependency Injection)
 */
//use \phputil\di\DI;
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

//$phoneRepository = DI::create('PhoneRepository');
//$phone = new \App\Phone\Phone();
//$phone->setDdd(22);
//$phone->setNumber(998512106);
//$retorno = $phoneRepository->create($phone);
//dd($retorno, $phone->getDdd());
//$phoneDb = $phoneRepository->select(1);
//dd($phoneDb);
//dd($phone, $phoneDb);
//var_dump($address);
//var_dump($addressRepository->user($address));