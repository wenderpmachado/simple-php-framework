<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 * created at: 15/05/2017 - 11:21
 */
interface EncoderMiddleware extends APIMiddleware {
	public static function encode($something);
}