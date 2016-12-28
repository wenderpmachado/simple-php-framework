<?php

require_once __DIR__ . '/../index.php';

use Phinx\Migration\AbstractMigration;
use \phputil\di\DI;

class Address extends AbstractMigration
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
        $repository = DI::create('Address'.'Repository');
        $repository->createTableWithPhinx($this);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable(strtolower('Address'));
    }
}