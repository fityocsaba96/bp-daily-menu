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
        $today = date('Y-m-d');
        return $this->getMenusBetweenInterval($today, $today);
    }

    public function getMenusBetweenInterval(string $fromDate, string $toDate) {
        $sql = 'SELECT restaurant, date, price, menu FROM daily_menu WHERE date BETWEEN :fromDate AND :toDate';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['fromDate' => $fromDate, 'toDate' => $toDate]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}