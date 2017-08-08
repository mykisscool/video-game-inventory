<?php

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

return [
  'paths' => [
      'migrations' => __DIR__ . '/db/migrations',
      'seeds' => __DIR__ . '/db/seeds',
  ],
  'environments' => [
      'default_migration_table' => 'phinxlog',
      'default_database' => 'production',
      'production' => [
        'adapter' => 'mysql',
        'host' => getenv('DBHOST_MIGRATIONS'),
        'name' => getenv('DBNAME'),
        'port' => getenv('DBPORT'),
        'user' => getenv('DBUSER'),
        'pass' => getenv('DBPASS'),
        'charset' => 'latin1'
      ],
  ]
];
