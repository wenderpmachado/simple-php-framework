<?php

/**
 * Repository Interface.
 *
 * @author	Wender Pinto Machado
 * @email wenderpmachado@gmail.com
 * @version 1.0
 */

namespace App\Database;

interface Repository {
    public function __construct(Database $database);
    public function getDatabase();
}