<?php
/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 */

namespace App\Phone;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class PhoneController {
	private $repository;

	public function __construct(PhoneRepository $repository) {
		$this->repository = $repository;
	}

	public function index(Request $request, Response $response, $args) {

	}

	public function store(Request $request, Response $response, $args) {
		try{
			$object = $this->requestToObject($request);
			$this->repository->store($object);
			return $object;
		}catch (Exception $e){

		}
	}

	private function requestToObject(Request $request) {
		$params = $request->getQueryParams();

		$object = new Phone();
		$object->setDdd($params['ddd']);
		$object->setNumber($params['number']);
		return $object;
	}
}