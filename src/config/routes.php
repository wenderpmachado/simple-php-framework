<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function(Request $request, Response $response, $args){
	$this->renderer->render($response, 'class-creator.html', $args);
});

require getenv('BASE_DIR') . getenv('HELPER_DIR') . 'class-creator' . '/' . 'ClassCreatorRoutes.php';

require getenv('BASE_DIR') . getenv('APP_DIR') . 'company' . '/' . 'CompanyRoutes.php';