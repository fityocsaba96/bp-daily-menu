<?php

namespace Tests\Dao;

use BpDailyMenu\Dao\RestaurantDao;
use BpDailyMenu\PDOFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class RestaurantDaoTest extends TestCase {

    /**
     * @var PDO
     */
    private static $pdo;
    /**
     * @var RestaurantDao
     */
    private static $dao;

    public static function setUpBeforeClass(): void {
        self::$pdo = (new PDOFactory)->createWithDBName();
        self::$dao = new RestaurantDao(self::$pdo);
    }

    protected function setUp(): void {
        self::$pdo->query('TRUNCATE TABLE restaurant');
    }

    /**
     * @test
     */
    public function list_tableIsEmpty_returnsEmptyArray() {
        $this->assertEquals([], self::$dao->list());
    }

    /**
     * @test
     */
    public function list_tableHasMultipleRestaurants_returnsArrayOfRestaurants() {
        $restaurants = [
            [
                'table_name' => 'Test table_name 1',
                'name' => 'Test name 1',
                'menu_url' => 'Test menu_url 1',
                'map_url' => 'Test map_url 1'
            ],
            [
                'table_name' => 'Test table_name 2',
                'name' => 'Test name 2',
                'menu_url' => 'Test menu_url 2',
                'map_url' => 'Test map_url 2'
            ]
        ];
        $this->saveRestaurants($restaurants);
        $this->assertEquals($restaurants, self::$dao->list());
    }

    private function saveRestaurants($restaurants): void {
        foreach ($restaurants as $restaurant) {
            $statement = self::$pdo->prepare("INSERT INTO restaurant (table_name, name, menu_url, map_url) VALUES (:table_name, :name, :menu_url, :map_url)");
            $statement->execute($restaurant);
        }
    }
}