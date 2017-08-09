<?php

namespace App\Address;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Core\Database\DefaultRestController;

class AddressController extends DefaultRestController {
	public function __construct(AddressRepository $repository) {
		parent::__construct($repository);
	}

	public function create(Request $request, Response $response, $args) {
		return new \NotImplementedException();
	}

	public function requestToObject($args) {
		$args = $this->sanitizeArgs($args);

		$object = new Address();
		if(isset($args['id']) && $args['id'] != null)
			$object->setId($args['id']);
		$object->setRoad($args['road']);
		$object->setUser($args['user_id']);
		return $object;
	}
}