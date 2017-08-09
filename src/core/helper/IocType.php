<?php

/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 0.0
 * created at: 17/05/2017 - 13:51
 */

namespace Core\Helper;

use MyCLabs\Enum\Enum;

class IocType extends Enum {
	const CONTROLLER = 'controller';
	const REPOSITORY = 'repository';
}