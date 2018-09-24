<?php

namespace BpDailyMenu\Dao;

use PDO;

class HealthCheckDao {

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getDatabaseName(): ?string {
        $sql = 'SELECT DATABASE()';
        return $this->pdo->query($sql)->fetchColumn();
    }
}