<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.8
 * created at: 15/05/2017 - 10:37
 */
abstract class APITemplateMethod implements SlimAPI, APIFacade {
	public final function run() {
		self::preMiddleware();
		self::validator();
		self::act();
		self::postMiddleware();
		self::encoder();
	}
}
