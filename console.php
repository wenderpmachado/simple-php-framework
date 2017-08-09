#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use \Core\Helper\Commands\MakeControllerCommand;
use \Core\Helper\Commands\MakeModelCommand;
use \Core\Helper\Commands\MakeRepositoryCommand;
use \Core\Helper\Commands\MakeRoutesCommand;

$application = new Application();

// register commands

$application->add(new MakeControllerCommand());
$application->add(new MakeModelCommand());
$application->add(new MakeRepositoryCommand());
$application->add(new MakeRoutesCommand());

// end register commands

$application->run();