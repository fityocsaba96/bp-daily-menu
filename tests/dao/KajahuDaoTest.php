<?php

namespace Tests\Dao;

use BpDailyMenu\Dao\KajahuDao;
use BpDailyMenu\PDOFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class KajahuDaoTest extends TestCase {

    /**
     * @var PDO
     */
    private static $pdo;
    /**
     * @var KajahuDao
     */
    private static $dao;

    public static function setUpBeforeClass(): void {
        self::$pdo = (new PDOFactory)->createWithDBName();
        self::$dao = new KajahuDao(self::$pdo);
    }

    protected function setUp(): void {
        self::$pdo->query('TRUNCATE TABLE kajahu');
    }

    /**
     * @test
     */
    public function getDailyMenu_noMenuForToday_returnsFalse() {
        $this->assertFalse(self::$dao->getDailyMenu());
    }

    /**
     * @test
     */
    public function getDailyMenu_thereIsMenuForToday_returnsMenu() {
        $menu = [
            'date' => date('Y-m-d'),
            'price' => 1000,
            'soup' => 'Test soup',
            'dish' => 'Test dish',
            'dessert' => 'Test dessert'
        ];
        $this->saveDailyMenu($menu);
        $this->assertEquals($menu, self::$dao->getDailyMenu());
    }

    private function saveDailyMenu($menu): void {
        $statement = self::$pdo->prepare("INSERT INTO kajahu (date, price, soup, dish, dessert) VALUES (:date, :price, :soup, :dish, :dessert)");
        $statement->execute($menu);
    }
}