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
        $sql = 'SELECT table_name, name, menu_url, map_url FROM restaurant';
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}