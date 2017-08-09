<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

require getenv('BASE_DIR') . getenv('CONFIG_DIR') . getenv('IOC_CONFIG_FILE');

require getenv('BASE_DIR') . getenv('HELPER_DIR') . 'Util.php';