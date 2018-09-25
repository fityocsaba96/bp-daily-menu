<?php

namespace BpDailyMenu\Dao;

use PDO;

class DailyMenuDao {

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getDailyMenu() {
        return false;
    }
}