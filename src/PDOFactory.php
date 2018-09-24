<?php

namespace BpDailyMenu;

use PDO;

class PDOFactory {

    public function createWithoutDBName(): PDO {
        return $this->create(false);
    }

    public function createWithDBName(): PDO {
        return $this->create(true);
    }

    private function create(bool $hasDBName): PDO {
        $dsn = 'mysql:host=' . getenv('DB_HOST') . ';charset=utf8mb4';
        if ($hasDBName)
            $dsn .= ';dbname=' . getenv('DB_NAME');
        return new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
    }
}