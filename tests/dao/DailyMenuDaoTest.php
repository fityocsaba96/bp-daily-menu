<?php

namespace Tests\Dao;

use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\PDOFactory;
use DateTime;
use PDO;
use PHPUnit\Framework\TestCase;

class DailyMenuDaoTest extends TestCase {

    /**
     * @var PDO
     */
    private static $pdo;
    /**
     * @var DailyMenuDao
     */
    private static $dao;

    public static function setUpBeforeClass(): void {
        self::$pdo = (new PDOFactory)->createWithDBName();
        self::$dao = new DailyMenuDao(self::$pdo);
    }

    protected function setUp(): void {
        self::$pdo->query('TRUNCATE TABLE daily_menu');
    }

    /**
     * @test
     */
    public function getDailyMenu_noMenuForToday_returnsFalse() {
        $this->assertFalse(self::$dao->getDailyMenu());
    }

//    /**
//     * @test
//     */
//    public function getDailyMenu_thereIsMenuForToday_returnsMenu() {
//        $menu = [
//            'date' => date('Y-m-d'),
//            'price' => 1000,
//            'soup' => 'Test soup',
//            'dish' => 'Test dish'
//        ];
//        $this->saveDailyMenu($menu);
//        $this->assertEquals($menu, self::$dao->getDailyMenu());
//    }
//
//    private function saveDailyMenu($menu): void {
//        $statement = self::$pdo->prepare("INSERT INTO bonnie (date, price, soup, dish) VALUES (:date, :price, :soup, :dish)");
//        $statement->execute($menu);
//    }

//    /**
//     * @test
//     */
//    public function getDailyMenu_thereIsMenuButNotForToday_returnsFalse() {
//        $menu = [
//            'date' => (new DateTime('yesterday'))->format('Y-m-d'),
//            'price' => 1000,
//            'soup' => 'Test soup',
//            'dish' => 'Test dish'
//        ];
//        $this->saveDailyMenu($menu);
//        $this->assertFalse(self::$dao->getDailyMenu());
//    }
}