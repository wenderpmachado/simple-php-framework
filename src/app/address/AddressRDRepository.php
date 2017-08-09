<?php

namespace App\Address;

use App\User\User;
use Core\Database\DefaultRDRepository;
use Phinx\Migration\MigrationInterface;

class AddressRDRepository extends DefaultRDRepository implements AddressRepository {
	public function recordToObject($record, $blocks = []){
		$address = new Address();
		$address->setId($record['id']);
		$address->setRoad($record['road']);
        $address->setUser($this->user($address));
		return $address;
	}

	public function objectToRecord($address, $blocks = []){
		return [
			'id' => $address->getId(),
			'road' => $address->getRoad(),
			'user_id' => ($address->getUser() instanceof User) ? $address->getUser()->getId() : $address->getUser()
		];
	}

	public function getTableName(){
		return 'address';
	}

	public function createTableWithPhinx(MigrationInterface $migration){
		$table = $migration->table($this->getTableName());
		return $table->addColumn('road', 'string')
                     ->addColumn('user_id', 'integer')
					 ->addColumn('created', 'datetime', array('default' => 'CURRENT_TIMESTAMP'))
					 ->addColumn('updated', 'datetime', array('null' => true))
					 ->create();
	}

    public function user($address){
        return $this->belongsTo($address, '\App\User');
    }
}