<?php

namespace Tests\Action;

use BpDailyMenu\AppBuilder;
use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\PDOFactory;
use BpDailyMenu\RestaurantCatalog;
use PDO;
use PHPUnit\Framework\TestCase;
use Tests\Helper\WebTestCase;

class TodaysMenuActionTest extends TestCase {

    use WebTestCase;

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
    public function invoke_containsCurrentDate() {
        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains(date('Y-m-d'), (string) $response->getBody());
    }

    /**
     * @test
     */
    public function invoke_noMenuForTodayForAllRestaurants_notContainsRestaurantsData() {
        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());

        $restaurants = RestaurantCatalog::getAll();
        foreach ($restaurants as $restaurant) {
            $this->assertNotContains($restaurant['name'], (string) $response->getBody());
            $this->assertNotContains($restaurant['map_url'], (string) $response->getBody());
        }
    }

//    /**
//     * @test
//     */
//    public function invoke_thereIsMenuForTodayForAllRestaurants_containsMenuForEveryRestaurant() {
//        $date = date('Y-m-d');
//        $test = [
//            ['1000', 'Test soup 1', 'Test dish 1', 'Test dessert 1'],
//            ['1100', 'Test soup 2', 'Test dish 2'],
//            ['1200', 'Test soup 3', 'Test dish 3'],
//            ['1300', 'Test soup 4', 'Test dish 4', 'Test drink 4']
//        ];
//        self::$pdo->query("INSERT INTO kajahu (date, price, soup, dish, dessert) VALUES ('$date', {$test[0][0]}, '{$test[0][1]}', '{$test[0][2]}', '{$test[0][3]}')");
//        self::$pdo->query("INSERT INTO bonnie (date, price, soup, dish) VALUES ('$date', {$test[1][0]}, '{$test[1][1]}', '{$test[1][2]}')");
//        self::$pdo->query("INSERT INTO muzikum (date, price, soup, dish) VALUES ('$date', {$test[2][0]}, '{$test[2][1]}', '{$test[2][2]}')");
//        self::$pdo->query("INSERT INTO vendiak (date, price, soup, dish, drink) VALUES ('$date', {$test[3][0]}, '{$test[3][1]}', '{$test[3][2]}', '{$test[3][3]}')");
//
//        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
//        $this->assertEquals(200, $response->getStatusCode());
//
//        $this->assertContains($date, (string) $response->getBody());
//        foreach ($test as $restaurant) {
//            foreach ($restaurant as $value) {
//                $this->assertContains($value, (string) $response->getBody());
//            }
//        }
//
//        $restaurants = self::$restaurantDao->list();
//        foreach ($restaurants as $restaurant) {
//            $this->assertNotContains('No menu found for ' . $restaurant['name'], (string) $response->getBody());
//        }
//    }
}