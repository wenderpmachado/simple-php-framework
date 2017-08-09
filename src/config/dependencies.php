<?php

use \phputil\di\DI;

global $app;

$container = $app->getContainer();

$container['renderer'] = function ($c) {
	$settings = $c->get('settings')['renderer'];
	return new \Slim\Views\PhpRenderer($settings['template_path']);
};

require getenv('BASE_DIR') . getenv("CONFIG_DIR") . getenv("SLIM_IOC_CONFIG_FILE");