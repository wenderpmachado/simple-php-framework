<?php
/**
 * @author Phillip Harrington
 * @email philsown@gmail.com
 */

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

return [
    'paths' => [
        'migrations' => __DIR__ . '/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
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