<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

define('IOC_CONFIG_FILE', __DIR__ . '/config/ioc.php');
define('ROUTES_FILE', __DIR__ . '/config/routes.php');

require_once IOC_CONFIG_FILE;

//require_once ROUTES_FILE;

require_once 'app/examples.php';