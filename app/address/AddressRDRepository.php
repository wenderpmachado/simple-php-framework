<?php

namespace App\Address;

use App\Database\DefaultRDRepository;

class AddressRDRepository extends DefaultRDRepository implements AddressRepository {
	public function recordToObject($record, $blocks = []){
		$address = new Address();
		$address->setId($record['id']);
		$address->setRoad($record['road']);
		return $address;
	}

	public function objectToRecord($address, $blocks = []){
		return [
			'id' => $address->getId(),
			'road' => $address->getRoad()
		];
	}

	public function getTableName(){
		return 'address';
	}
}