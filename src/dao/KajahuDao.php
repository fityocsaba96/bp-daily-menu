<?php

namespace BpDailyMenu\Dao;

use PDO;

class KajahuDao {

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getDailyMenu(): array {
        return [];
    }
}