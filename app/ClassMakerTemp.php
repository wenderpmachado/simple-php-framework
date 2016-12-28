<?php

require_once '../index.php';

use Core\helper\ClassMaker;

$classMaker = new ClassMaker();
$className = 'Address';
$parameters = [
    'id' => 'integer',
    'road' => 'string'
];
echo $classMaker->makeModel($className, $parameters);
echo $classMaker->makeRepository($className, $parameters);