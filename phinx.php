<?php

$dsn = 'mysql:host=mysql;charset=utf8mb4';
$pdo = new PDO($dsn, 'academy', 'academy');
$pdo->query('CREATE DATABASE IF NOT EXISTS bp_daily_menu');
$pdo->query('CREATE DATABASE IF NOT EXISTS bp_daily_menu_test');

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => 'mysql',
            'name' => 'bp_daily_menu',
            'user' => 'academy',
            'pass' => 'academy',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'test' => [
            'adapter' => 'mysql',
            'host' => 'mysql',
            'name' => 'bp_daily_menu_test',
            'user' => 'academy',
            'pass' => 'academy',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];