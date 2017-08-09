<?php

namespace App\Address;

global $app;

$app->group('/address', function() use ($app) {
	$app->get('[/]', 'AddressController:index');
	$app->get('/{id:[0-9]+}[/]', 'AddressController:edit');
	$app->put('/{id:[0-9]+}[/]', 'AddressController:update');
	$app->delete('/{id:[0-9]+}[/]', 'AddressController:delete');
	$app->get('/create[/]', 'AddressController:create');
	$app->post('/create[/]', 'AddressController:store');
});