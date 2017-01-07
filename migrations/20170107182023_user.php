<?php

require_once '../index.php';

use Phinx\Migration\AbstractMigration;
use \phputil\di\DI;

class User extends AbstractMigration
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
        $repository = DI::create('User'.'Repository');
        $repository->createTableWithPhinx($this);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable(strtolower('User'));
    }
}