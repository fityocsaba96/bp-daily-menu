<?php

namespace Tests\Action;

use BpDailyMenu\AppBuilder;
use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\PDOFactory;
use BpDailyMenu\RestaurantCatalog;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
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
        $this->responseNotContainsRestaurantData($response, $restaurants);
    }

    /**
     * @test
     */
    public function invoke_thereIsMenuForTodayForAllRestaurants_containsRestaurantDataAndMenuForEveryRestaurant() {
        $restaurants = RestaurantCatalog::getAll();
        $menus = $this->createMenusFromDateAndRestaurantKeys(date('Y-m-d'), array_keys($restaurants));
        $this->insertMenus($menus);

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());

        $this->responseContainsRestaurantData($response, $restaurants);
        $this->responseContainsMenuData($response, $menus);
    }

    /**
     * @test
     */
    public function invoke_thereIsMenuForTodayForSomeRestaurants_containsRestaurantDataAndMenuForSomeRestaurant() {
        $restaurants = RestaurantCatalog::getAll();
        $this->assertGreaterThan(1, count($restaurants));
        $firstHalf = array_slice($restaurants, 0, count($restaurants) / 2);
        $secondHalf = array_slice($restaurants, count($restaurants) / 2);

        $menus = $this->createMenusFromDateAndRestaurantKeys(date('Y-m-d'), array_keys($firstHalf));
        $this->insertMenus($menus);

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/today');
        $this->assertEquals(200, $response->getStatusCode());

        $this->responseContainsRestaurantData($response, $firstHalf);
        $this->responseContainsMenuData($response, $menus);
        $this->responseNotContainsRestaurantData($response, $secondHalf);
    }

    private function createMenusFromDateAndRestaurantKeys(string $date, array $restaurantKeys): array {
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

    private function responseNotContainsRestaurantData(ResponseInterface $response, array $restaurants) {
        foreach ($restaurants as $restaurant) {
            $this->assertNotContains($restaurant['name'], (string) $response->getBody());
            $this->assertNotContains($restaurant['map_url'], (string) $response->getBody());
        }
    }

    private function responseContainsRestaurantData(ResponseInterface $response, array $restaurants) {
        foreach ($restaurants as $restaurant) {
            $this->assertContains($restaurant['name'], (string) $response->getBody());
            $this->assertContains($restaurant['map_url'], (string) $response->getBody());
        }
    }

    private function responseContainsMenuData(ResponseInterface $response, array $menus) {
        foreach ($menus as $menu) {
            $this->assertContains((string) $menu['price'], (string) $response->getBody());
            $this->assertContains($menu['menu'], (string) $response->getBody());
        }
    }

    private function insertMenus(array $menus) {
        foreach ($menus as $menu) {
            $statement = self::$pdo->prepare("INSERT INTO daily_menu (restaurant, date, price, menu) VALUES (:restaurant, :date, :price, :menu)");
            $statement->execute($menu);
        }
    }
}