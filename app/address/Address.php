<?php

namespace App\Address;

use phputil\traits\GetterSetterWithBuilder;

class Address {
	use GetterSetterWithBuilder;

	private $id = 0;
	private $road = '';
}