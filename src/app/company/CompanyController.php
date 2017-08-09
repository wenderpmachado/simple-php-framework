<?php

namespace App\Company;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Core\Database\DefaultRestController;

class CompanyController extends DefaultRestController {
	public function __construct(CompanyRepository $repository) {
		parent::__construct($repository);
	}

	public function create(Request $request, Response $response, $args) {
		return new \NotImplementedException();
	}

	public function requestToObject($args) {
		$args = $this->sanitizeArgs($args);

		$company = new Company();
		if(isset($args['id']) && $args['id'] != null)
			$company->setId($args['id']);
		$company->setName($args['name']);
		return $company;
	}
}