<?php

require_once '../vendor/autoload.php';

$classMaker = new ClassMaker();
$parameters = [
    'id' => 'integer',
    'road' => 'string'
];
$className = 'Address';
echo $classMaker->makeModel($className, $parameters);
echo $classMaker->makeRepository($className, $parameters);