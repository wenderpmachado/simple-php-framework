<?php

namespace App\Phone;

use Core\Database\DefaultRDRepository;
use Phinx\Migration\MigrationInterface;

class PhoneRDRepository extends DefaultRDRepository implements PhoneRepository {
	public function recordToObject($record, $blocks = []){
		$phone = new Phone();
		$phone->setId($record['id']);
		$phone->setDdd($record['ddd']);
		$phone->setNumber($record['number']);
		return $phone;
	}

	public function objectToRecord($phone, $blocks = []){
		return [
			'id' => $phone->getId(),
			'ddd' => $phone->getDdd(),
			'number' => $phone->getNumber(),
		];
	}

	public function getTableName(){
		return 'phone';
	}

	public function createTableWithPhinx(MigrationInterface $migration){
		$table = $migration->table($this->getTableName());
		return $table->addColumn('ddd', 'integer')
					 ->addColumn('number', 'integer')
					 ->addColumn('created', 'datetime', array('default' => 'CURRENT_TIMESTAMP'))
					 ->addColumn('updated', 'datetime', array('null' => true))
					 ->create();
	}
}