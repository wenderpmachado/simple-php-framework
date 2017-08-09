<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 * created at: 15/05/2017 - 11:22
 */
abstract class JsonEncoderMiddleware implements EncoderMiddleware {
	public static function encode($something) {
		return JSON::encode($something);
	}
}