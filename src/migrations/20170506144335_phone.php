<?php

require_once __DIR__ . '/../pre-config.php';

use Phinx\Migration\AbstractMigration;
use \phputil\di\DI;

class Phone extends AbstractMigration
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
        $repository = DI::create('Phone'.'Repository');
        $repository->createTableWithPhinx($this);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable(strtolower('Phone'));
    }
}