<?php

require_once '../index.php';

$classMaker = new ClassMaker();
$parameters = [
    'id' => 'integer',
    'road' => 'string'
];
$className = 'Address';
echo $classMaker->makeModel($className, $parameters);
echo $classMaker->makeRepository($className, $parameters);