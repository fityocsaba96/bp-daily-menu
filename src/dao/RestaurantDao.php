<?php

namespace BpDailyMenu\Dao;

use PDO;

class RestaurantDao {

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function list(): array {
        return [];
    }
}