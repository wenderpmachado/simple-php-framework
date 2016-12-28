<?php

use Phinx\Migration\AbstractMigration;
use Core\Helper\ClassMaker;

$rows = ClassMaker::fieldsToMigration();

class TesteTable2 extends AbstractMigration
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
        // create the table
        $table = $this->table(TesteTable2);
        $table->addColumn('id', 'integer')
              ->addColumn('created', 'datetime')
              ->addColumn('updated', 'datetime')
              ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable(TesteTable2);
    }
}