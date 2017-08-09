<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 * created at: 15/05/2017 - 11:37
 */
interface API {
	public function preMiddleware();
	public function validator();
	public function act($request, $response, $args = []);
	public function postMiddleware();
	public function encoder();
}