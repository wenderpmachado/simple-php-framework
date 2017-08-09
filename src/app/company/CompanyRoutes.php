<?php

namespace App\Company;

global $app;

$app->group('/company', function() use ($app) {
	$app->get('[/]', 'CompanyController:index');
	$app->get('/{id:[0-9]+}[/]', 'CompanyController:edit');
	$app->put('/{id:[0-9]+}[/]', 'CompanyController:update');
	$app->delete('/{id:[0-9]+}[/]', 'CompanyController:delete');
	$app->get('/create[/]', 'CompanyController:create');
	$app->post('/create[/]', 'CompanyController:store');
});
