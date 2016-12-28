<?php

/**
 * Interface for integration with Phinx.
 *
 * @author	Wender Pinto Machado
 * @email wenderpmachado@gmail.com
 * @version 0.7
 */

namespace Core\Database;

use Phinx\Migration\MigrationInterface;

interface PhinxIntegration {
    public function createTableWithPhinx(MigrationInterface $migration);
}