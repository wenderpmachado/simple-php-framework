<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 */

namespace Core\Database;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface RestController {
	public function index(Request $request, Response $response, $args);
	public function create(Request $request, Response $response, $args);
	public function store(Request $request, Response $response, $args);
	public function show(Request $request, Response $response, $args);
	public function edit(Request $request, Response $response, $args);
	public function update(Request $request, Response $response, $args);
	public function delete(Request $request, Response $response, $args);
}