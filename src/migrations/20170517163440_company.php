<?php

require_once __DIR__ . '/../pre-config.php';

use Phinx\Migration\AbstractMigration;
use \phputil\di\DI;

class Company extends AbstractMigration
{
    /*
    public function change()
    {

    }
    */

    /**
     * Migrate Up.
     */
    public function up()
    {
        $repository = DI::create('Company'.'Repository');
        $repository->createTableWithPhinx($this);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable(strtolower('Company'));
    }
}
