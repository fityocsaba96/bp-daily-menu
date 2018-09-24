<?php

namespace BpDailyMenu\Dao;

use PDO;

class BonnieDao implements Restaurant {

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getDailyMenu() {
        $date = date('Y-m-d');
        $sql = "SELECT date, price, soup, dish FROM bonnie WHERE date=:date";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['date' => $date]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}