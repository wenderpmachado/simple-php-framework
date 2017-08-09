<?php

namespace App\Phone;

use phputil\traits\GetterSetterWithBuilder;

class Phone {
	use GetterSetterWithBuilder;

	private $id = 0;
	private $ddd = 0;
	private $number = 0;
}