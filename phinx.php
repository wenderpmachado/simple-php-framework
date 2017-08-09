<?php
/**
 * @author 	Phillip Harrington <philsown@gmail.com>
 * updates	Wender Pinto Machado <wenderpmachado@gmail.com>
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

return [
    'paths' => [
        'migrations' => getenv('BASE_DIR') . getenv('MIGRATIONS_DIR'),
    ],
    'environments' => [
        'default_migration_table' => getenv('PHINX_DEFAULT_MIGRATION_TABLE'),
        'default_database' => getenv('APP_ENV'),
        'develop' => [
            'adapter' => getenv('DB_ADAPTER'),
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_DATABASE'),
            'user' => getenv('DB_USERNAME'),
            'pass' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT'),
        ],
    ],
];