<?php

/**
 * Repository Interface.
 *
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 * @version 1.0
 */

namespace Core\Database;

interface Repository {
    public function __construct(Database $database);
    public function getDatabase();
}