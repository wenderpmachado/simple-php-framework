<?php

require 'pre-config.php';

$settings = require getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("SLIM_CONFIG_FILE");
$app = new \Slim\App($settings);
require getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("SLIM_DEPENDENCIES_FILE");
require getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("ROUTES_FILE");
$app->run();

//require 'app/test.php';