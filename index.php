<?php

require_once __DIR__ . '/vendor/autoload.php';

use \App\BancoDados\BancoDados;
use \phputil\di\DI;
use \phputil\PDOWrapper;
use \App\Usuario\Usuario;
use \App\Conta\Conta;
use \App\Usuario\ColecaoDeUsuarioEmBDR;
use \App\Conta\ColecaoDeContaEmBDR;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/* Configuração da Injeção de Dependências */
/* Configuração das classes bases */

DI::config(DI::let('PDOWrapper')->call(function(){
    return new PDOWrapper(BancoDados::buildPDO());
})->shared());

DI::config(DI::let('BancoDados')->call(function(){
    return new BancoDados(DI::create('PDOWrapper'));
})->shared());

/* Configuração das Coleções */

DI::config(DI::let('ColecaoDeUsuario')->call(function(){
    return new ColecaoDeUsuarioEmBDR(DI::create('BancoDados'));
})->shared());

DI::config(DI::let('ColecaoDeConta')->call(function(){
    return new ColecaoDeContaEmBDR(DI::create('BancoDados'));
})->shared());



//$relationalClassName = explode('\\', 'App\Endereco\Endereco')[2];
//$relationalExploded = explode('\\', $relationalClass);
//$relationalClassName = array_pop($relationalExploded);
//$relationalCollectionName = 'ColecaoDe' . $relationalClassName;
//$collection = DI::create($relationalCollectionName);

//$relationalClass = 'App\Usuario\Usuario';
//$collection = DI::create(\App\BancoDados\ColecaoEmBDRPadrao::classNameWithNamespaceToCollectionName($relationalClass));
//$colecaoDeUsuario = DI::create('ColecaoDeUsuario');
//$usuario = new Usuario();
//$usuario->setId(2);
//$relationalClass = 'App\Conta\Conta';
////var_dump($collection->hasMany($usuario, $relationalClass));
//
////print_r("--------------------------------------------------------------------------------------------------------------------------\n");
//
//$relationalClass = 'App\Conta\Conta';
//$collection = DI::create(\App\BancoDados\ColecaoEmBDRPadrao::classNameWithNamespaceToCollectionName($relationalClass));
//$colecaoDeUsuario = DI::create('ColecaoDeUsuario');
//$conta = new Conta();
//$conta->setId(1);
//$relationalClass = 'App\Usuario\Usuario';
////var_dump($collection->belongsToMany($conta, $relationalClass, $usuario->getId()));

//print_r("--------------------------------------------------------------------------------------------------------------------------\n");


$relationalClass = 'App\Usuario\Usuario';
$colecaoDeUsuario = DI::create('ColecaoDeUsuario');
$usuario = new Usuario();
$usuario->setId(2);
$relationalClass = 'App\Conta\Conta';
var_dump($colecaoDeUsuario->conta($usuario, $relationalClass));

print_r("--------------------------------------------------------------------------------------------------------------------------\n");

$relationalClass = 'App\Conta\Conta';
$colecaoDeConta = DI::create('ColecaoDeConta');
$conta = new Conta();
$conta->setId(1);
$relationalClass = 'App\Usuario\Usuario';
var_dump($colecaoDeConta->usuario($conta, $relationalClass, $usuario->getId()));

