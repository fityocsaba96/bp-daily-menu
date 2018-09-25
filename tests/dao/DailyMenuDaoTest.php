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
    public function getDailyMenu_noMenuForToday_returnsEmptyArray() {
        $this->assertEmpty(self::$dao->getDailyMenu());
    }

    /**
     * @test
     */
    public function getDailyMenu_thereIsMenuForToday_returnsMenu() {
        $date = date('Y-m-d');
        $menus = [
            [
                'date' => $date,
                'price' => 1000,
                'menu' => 'Test menu',
                'restaurant' => 'bonnie'
            ],
            [
                'date' => $date,
                'price' => 1100,
                'menu' => 'Test menu 2',
                'restaurant' => 'vendiak'
            ],
        ];
        $this->saveDailyMenus($menus);
        $this->assertEquals($menus, self::$dao->getDailyMenu());
    }

    private function saveDailyMenus($menus): void {
        foreach ($menus as $menu) {
            $statement = self::$pdo->prepare("INSERT INTO daily_menu (date, price, menu, restaurant) VALUES (:date, :price, :menu, :restaurant)");
            $statement->execute($menu);
        }
    }

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