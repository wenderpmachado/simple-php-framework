<?php
/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 */

namespace Core\Helper\ClassCreator;

global $app;

$app->group('/class-creator', function() use ($app) {
	$app->post('/create[/]', 'ClassCreatorController:create');
});