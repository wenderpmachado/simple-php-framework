<?php

namespace App\Company;

use Core\Database\DefaultRDRepository;
use Phinx\Migration\MigrationInterface;

class CompanyRDRepository extends DefaultRDRepository implements CompanyRepository {
	public function recordToObject($record, $blocks = []){
		$company = new Company();
		$company->setId($record['id']);
		$company->setName($record['name']);
		return $company;
	}

	public function objectToRecord($company, $blocks = []){
		return [
			'id' => $company->getId(),
			'name' => $company->getName(),
		];
	}

	public function getTableName(){
		return 'company';
	}

	public function createTableWithPhinx(MigrationInterface $migration){
		$table = $migration->table($this->getTableName());
		return $table->addColumn('name', 'integer')
					 ->addColumn('created', 'datetime', array('default' => 'CURRENT_TIMESTAMP'))
					 ->addColumn('updated', 'datetime', array('null' => true))
					 ->create();
	}
}