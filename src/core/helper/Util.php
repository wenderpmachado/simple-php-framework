<?php
/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 */

//namespace Core\Helper;

abstract class Util {

}

function dd(...$expressions) {
	foreach ($expressions as $expression)
		var_dump($expression);
	die();
}