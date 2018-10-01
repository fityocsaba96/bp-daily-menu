<?php

use BpDailyMenu\EnvLoader;
use BpDailyMenu\PDOFactory;

(new EnvLoader)();

function loadConfig(string $env): array {
    (new EnvLoader)($env);
    $pdo = (new PDOFactory)->createWithoutDbName();
    $pdo->query('CREATE DATABASE IF NOT EXISTS ' . getenv('DB_NAME'));
    $config = getConfigFromEnv();
    return $config;
}

function getConfigFromEnv(): array {
    return [
        'adapter' => 'mysql',
        'host' => getenv('DB_HOST'),
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'pass' => getenv('DB_PASS'),
        'port' => '3306',
        'charset' => 'utf8',
    ];
}

if (getenv('APPLICATION_ENV')) {
    $developmentConfig = loadConfig('development');
    $testConfig = loadConfig('test');
} else {
    $codeshipConfig = getConfigFromEnv();
}

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => $developmentConfig ?? [],
        'test' => $testConfig ?? [],
        'codeship' => $codeshipConfig ?? []
    ],
    'version_order' => 'creation'
];