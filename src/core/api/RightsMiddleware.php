<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 * created at: 15/05/2017 - 11:20
 */
abstract class RightsMiddleware implements AuthenticationMiddleware {
	public static function authenticate() {
		return true;
	}
}