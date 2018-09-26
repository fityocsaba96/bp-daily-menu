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

    /**
     * @test
     */
    public function invoke_thereIsMenuForTodayForAllRestaurants_containsRestaurantDataAndMenuForEveryRestaurant() {
        $menus = $this->createMenusFromDateAndRestaurantCatalog(date('Y-m-d'));
        foreach ($menus as $menu) {
            $statement = self::$pdo->prepare("INSERT INTO daily_menu (restaurant, date, price, menu) VALUES (:restaurant, :date, :price, :menu)");
            $statement->execute($menu);
        }

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());

        $restaurants = RestaurantCatalog::getAll();
        foreach ($restaurants as $restaurant) {
            $this->assertContains($restaurant['name'], (string) $response->getBody());
            $this->assertContains($restaurant['map_url'], (string) $response->getBody());
        }

        foreach ($menus as $menu) {
            $this->assertContains((string) $menu['price'], (string) $response->getBody());
            $this->assertContains($menu['menu'], (string) $response->getBody());
        }
    }

    private function createMenusFromDateAndRestaurantCatalog(string $date): array {
        $restaurantKeys = array_keys(RestaurantCatalog::getAll());
        $menus = [];
        for ($i = 0; $i < count($restaurantKeys); $i++) {
            $menus[] = [
                'date' => $date,
                'price' => 1000 + $i,
                'menu' => "Test menu $i",
                'restaurant' => $restaurantKeys[$i]
            ];
        }
        return $menus;
    }
}