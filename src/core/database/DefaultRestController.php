<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 */

namespace Core\Database;

use phputil\JSON;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class DefaultRestController implements RestController {
	private $repository;

	public function __construct(DefaultRepository $repository) {
		$this->repository = $repository;
	}

	public function index(Request $request, Response $response, $args) {
		try{
			$response = $this->defineHeader($response);
			$args = $this->sanitizeArgs($args);

			$limit  = (isset($args['limit'])) ? $args['limit'] : 100;
			$offset = (isset($args['offset'])) ? $args['offset'] : 0;
			$result = $this->repository->selectAll($limit, $offset);
			$this->respond($response, $result);
		} catch (\Exception $e){

		}
	}

	public function show(Request $request, Response $response, $args) {
		try{
			$response = $this->defineHeader($response);
			$args = $this->sanitizeArgs($args);

			$id = $args['id'];
			if($id) {
				$result = $this->repository->delete($id);
				$this->respond($response, $result);
			}
		} catch (\Exception $e){

		}
	}

	public function edit(Request $request, Response $response, $args) {
		try{
			$response = $this->defineHeader($response);
			$args = $this->sanitizeArgs($args);

			$id = $args['id'];
			if($id) {
				$result = $this->repository->select($id);
				$response = $response->withStatus(302);
				$this->respond($response, $result);
			} else {
				$response = $response->withStatus(404);
				$this->respond($response, ["success" => false]);
			}
		} catch (\Exception $e){

		}
	}

	public function store(Request $request, Response $response, $args) {
		try{
			$response = $this->defineHeader($response);
			$args = $this->sanitizeArgs($request->getParsedBody());
			$object = $this->requestToObject($args);

			if($this->repository->store($object)){
				$response = $response->withStatus(201);
				$this->respond($response, ["success" => true]);
			}else{
				$response = $response->withStatus(400);
				$this->respond($response, ["success" => false]);
			}
		} catch (\Exception $e){

		}
	}

	public function update(Request $request, Response $response, $args) {
		try{
			$response = $this->defineHeader($response);
			$args = $this->sanitizeArgs($request->getParsedBody());
			$object = $this->requestToObject($args);

			if($this->repository->update($object)){
				$response = $response->withStatus(200);
				$this->respond($response, ["success" => true]);
			} else {
				$response = $response->withStatus(304);
				$this->respond($response, ["success" => false]);
			}
		} catch (\Exception $e){

		}
	}

	public function delete(Request $request, Response $response, $args) {
		try{
			$response = $this->defineHeader($response);
			$args = $this->sanitizeArgs($args);

			if($this->repository->delete($args['id'])){
				$response = $response->withStatus(200);
				$this->respond($response, ["success" => true]);
			} else {
				$response = $response->withStatus(404);
				$this->respond($response, ["success" => false]);
			}
		} catch (\Exception $e){

		}
	}

	protected function defineHeader(Response $response) {
		return $response->withHeader('Content-type', 'application/json');
	}

	protected function respond(Response $response, $object) {
		$body = $response->getBody();
		$body->write(JSON::encode($object));
	}

	protected function sanitizeArgs(array $args) {
		return filter_var_array($args, FILTER_SANITIZE_STRING);
	}

	/*
	* Functions that child classes should implement.
	*/

	public abstract function requestToObject($request);
	public abstract function create(Request $request, Response $response, $args);
}