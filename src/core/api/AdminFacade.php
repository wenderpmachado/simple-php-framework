<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 * created at: 15/05/2017 - 11:33
 */
class AdminFacade implements API {
	public function preMiddleware() {
		LoginMiddleware::authenticate();
		RightsMiddleware::authenticate();
	}

	public function act($request, $response, $args = []) {
		throw new NotImplementedException();
	}

	public function encoder() {
		throw new NotImplementedException();
	}

	public function postMiddleware() {
		throw new NotImplementedException();
	}

	public function validator() {
		throw new NotImplementedException();
	}
}