<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 * created at: 15/05/2017 - 11:17
 */
interface AuthenticationMiddleware extends APIMiddleware {
	public static function authenticate();
}