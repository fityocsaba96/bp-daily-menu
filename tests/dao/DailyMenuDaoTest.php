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
        $menus = $this->createMenusFromDates([$date, $date]);
        $this->saveDailyMenus($menus);
        $this->assertEquals($menus, self::$dao->getDailyMenu());
    }

    /**
     * @test
     */
    public function getDailyMenu_thereIsMenuForTodayAndAnotherDay_returnsMenuForToday() {
        $menus = $this->createMenusFromDates([date('Y-m-d'), (new DateTime('tomorrow'))->format('Y-m-d')]);
        $this->saveDailyMenus($menus);
        $this->assertEquals([$menus[0]], self::$dao->getDailyMenu());
    }

    /**
     * @test
     */
    public function getMenusBetweenInterval_NoMenusInInterval_returnsEmptyArray() {
        $this->assertEmpty(self::$dao->getMenusBetweenInterval('2018-01-12', '2018-01-13'));
    }

    /**
     * @test
     */
    public function getMenusBetweenInterval_thereIsMenuInInterval_returnsArrayContainingTheMenu() {
        $menus = $this->createMenusFromDates(['2018-01-12']);
        $this->saveDailyMenus($menus);
        $this->assertEquals($menus, self::$dao->getMenusBetweenInterval('2018-01-12', '2018-01-13'));
    }

    /**
     * @test
     */
    public function getMenusBetweenInterval_thereAreMenusInInterval_returnsArrayContainingTheMenus() {
        $menus = $this->createMenusFromDates(['2018-01-12', '2018-01-13']);
        $this->saveDailyMenus($menus);
        $this->assertEquals($menus, self::$dao->getMenusBetweenInterval('2018-01-12', '2018-01-13'));
    }

    /**
     * @test
     */
    public function getMenusBetweenInterval_thereAreMenusInIntervalAndNotInInterval_returnsArrayContainingTheMenusInInterval() {
        $menusInInterval = $this->createMenusFromDates(['2018-01-12', '2018-01-13']);
        $this->saveDailyMenus($menusInInterval);
        $menusNotInInterval = $this->createMenusFromDates(['2019-01-12', '2019-01-13']);
        $this->saveDailyMenus($menusNotInInterval);
        $this->assertEquals($menusInInterval, self::$dao->getMenusBetweenInterval('2018-01-12', '2018-01-13'));
    }

    private function saveDailyMenus($menus): void {
        foreach ($menus as $menu) {
            $statement = self::$pdo->prepare("INSERT INTO daily_menu (date, price, menu, restaurant) VALUES (:date, :price, :menu, :restaurant)");
            $statement->execute($menu);
        }
    }

    private function createMenusFromDates(array $dates): array {
        $menus = [];
        for ($i = 0; $i < count($dates); $i++) {
            $menus[] = [
                'date' => $dates[$i],
                'price' => 1000 + $i,
                'menu' => "Test menu $i",
                'restaurant' => "Test restaurant $i"
            ];
        }
        return $menus;
    }
}