<?php

namespace App\User;

use Core\Database\DefaultRDRepository;
use Phinx\Migration\MigrationInterface;

class UserRDRepository extends DefaultRDRepository implements UserRepository {
	public function recordToObject($record, $blocks = []){
		$user = new User();
		$user->setId($record['id']);
		$user->setName($record['name']);
		$user->setEmail($record['email']);
		return $user;
	}

	public function objectToRecord($user, $blocks = []){
		return [
			'id' => $user->getId(),
			'name' => $user->getName(),
			'email' => $user->getEmail()
		];
	}

	public function getTableName(){
		return 'user';
	}

	public function createTableWithPhinx(MigrationInterface $migration){
		$table = $migration->table($this->getTableName());
		return $table					 ->addColumn('name', 'string')
					 ->addColumn('email', 'string')
					 ->addColumn('created', 'datetime')
					 ->addColumn('updated', 'datetime')
					 ->create();
	}

    public function address($user){
        return $this->hasOne($user, '\App\Address');
    }
}