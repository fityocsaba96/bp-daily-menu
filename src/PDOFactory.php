<?php

namespace BpDailyMenu;

use PDO;

class PDOFactory {

    public function createWithoutDBName(): PDO {
        $dsn = 'mysql:host=' . getenv('DB_HOST') . ';charset=utf8mb4';
        return new PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
    }
}