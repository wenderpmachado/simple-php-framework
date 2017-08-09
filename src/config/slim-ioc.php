<?php

use \phputil\di\DI;

global $app;

$container = $app->getContainer();

$container['CompanyRepository'] = function ($container) { return DI::create('CompanyRepository'); };
$container['CompanyController'] = function ($container) { return DI::create('CompanyController'); };