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
        $sql = 'SELECT restaurant, date, price, menu FROM daily_menu WHERE date=:date';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['date' => date('Y-m-d')]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}