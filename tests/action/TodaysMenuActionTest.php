<?php

namespace Tests\Action;

use BpDailyMenu\AppBuilder;
use BpDailyMenu\Dao\BonnieDao;
use BpDailyMenu\Dao\KajahuDao;
use BpDailyMenu\Dao\MuzikumDao;
use BpDailyMenu\Dao\RestaurantDao;
use BpDailyMenu\Dao\VendiakDao;
use BpDailyMenu\PDOFactory;
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
     * @var KajahuDao
     */
    private static $kajahuDao;
    /**
     * @var RestaurantDao
     */
    private static $restaurantDao;
    /**
     * @var BonnieDao
     */
    private static $bonnieDao;
    /**
     * @var MuzikumDao
     */
    private static $muzikumDao;
    /**
     * @var VendiakDao
     */
    private static $vendiakDao;

    public static function setUpBeforeClass(): void {
        self::$pdo = (new PDOFactory)->createWithDBName();
        self::$restaurantDao = new RestaurantDao(self::$pdo);
        self::$kajahuDao = new KajahuDao(self::$pdo);
        self::$bonnieDao = new BonnieDao(self::$pdo);
        self::$muzikumDao = new MuzikumDao(self::$pdo);
        self::$vendiakDao = new VendiakDao(self::$pdo);

        self::$pdo->query('TRUNCATE TABLE restaurant');
        $restaurants = [
            [
                'table_name' => 'kajahu',
                'name' => 'Kajahu',
                'menu_url' => 'https://appif.kajahu.com/jdmenu?jseat=-&jlang=hu',
                'map_url' => 'https://goo.gl/maps/wgjZGzc3P822'
            ],
            [
                'table_name' => 'bonnie',
                'name' => 'Bonnie',
                'menu_url' => 'http://bonnierestro.hu/hu/napimenu',
                'map_url' => 'https://goo.gl/maps/WG2dHwT9AeG2'
            ],
            [
                'table_name' => 'muzikum',
                'name' => 'Muzikum',
                'menu_url' => 'http://muzikum.hu/heti-menu',
                'map_url' => 'https://goo.gl/maps/aSMrvAeLu6x'
            ],
            [
                'table_name' => 'vendiak',
                'name' => 'Véndiák',
                'menu_url' => 'http://www.vendiaketterem.hu/heti_ajanlat',
                'map_url' => 'https://goo.gl/maps/qqNxAny89rN2'
            ]
        ];
        self::saveRestaurants($restaurants);
    }

    protected function setUp(): void {
        self::$pdo->query('TRUNCATE TABLE kajahu');
        self::$pdo->query('TRUNCATE TABLE bonnie');
        self::$pdo->query('TRUNCATE TABLE muzikum');
        self::$pdo->query('TRUNCATE TABLE vendiak');
    }

    private static function saveRestaurants($restaurants): void {
        foreach ($restaurants as $restaurant) {
            $statement = self::$pdo->prepare("INSERT INTO restaurant (table_name, name, menu_url, map_url) VALUES (:table_name, :name, :menu_url, :map_url)");
            $statement->execute($restaurant);
        }
    }

    /**
     * @test
     */
    public function invoke_containsRestaurantNamesAndMaps() {
        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());

        $restaurants = self::$restaurantDao->list();
        foreach ($restaurants as $restaurant) {
            $this->assertContains($restaurant['name'], (string) $response->getBody());
            $this->assertContains($restaurant['map_url'], (string) $response->getBody());
        }
    }

    /**
     * @test
     */
    public function invoke_noMenuForTodayForAllRestaurants_containsNoticeForEveryRestaurant() {
        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());

        $restaurants = self::$restaurantDao->list();
        foreach ($restaurants as $restaurant) {
            $this->assertContains('No menu found for ' . $restaurant['name'], (string) $response->getBody());
        }
    }

    /**
     * @test
     */
    public function invoke_thereIsMenuForTodayForAllRestaurants_containsMenuForEveryRestaurant() {
        $date = date('Y-m-d');
        $test = [
            ['1000', 'Test soup 1', 'Test dish 1', 'Test dessert 1'],
            ['1100', 'Test soup 2', 'Test dish 2'],
            ['1200', 'Test soup 3', 'Test dish 3'],
            ['1300', 'Test soup 4', 'Test dish 4', 'Test drink 4']
        ];
        self::$pdo->query("INSERT INTO kajahu (date, price, soup, dish, dessert) VALUES ('$date', {$test[0][0]}, '{$test[0][1]}', '{$test[0][2]}', '{$test[0][3]}')");
        self::$pdo->query("INSERT INTO bonnie (date, price, soup, dish) VALUES ('$date', {$test[1][0]}, '{$test[1][1]}', '{$test[1][2]}')");
        self::$pdo->query("INSERT INTO muzikum (date, price, soup, dish) VALUES ('$date', {$test[2][0]}, '{$test[2][1]}', '{$test[2][2]}')");
        self::$pdo->query("INSERT INTO vendiak (date, price, soup, dish, drink) VALUES ('$date', {$test[3][0]}, '{$test[3][1]}', '{$test[3][2]}', '{$test[3][3]}')");

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertContains($date, (string) $response->getBody());
        foreach ($test as $restaurant) {
            foreach ($restaurant as $value) {
                $this->assertContains($value, (string) $response->getBody());
            }
        }

        $restaurants = self::$restaurantDao->list();
        foreach ($restaurants as $restaurant) {
            $this->assertNotContains('No menu found for ' . $restaurant['name'], (string) $response->getBody());
        }
    }
}