<?php

namespace App\User;

use phputil\traits\GetterSetterWithBuilder;

class User {
	use GetterSetterWithBuilder;

	private $id = 0;
	private $name = '';
	private $email = '';
}