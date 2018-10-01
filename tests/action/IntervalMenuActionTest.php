<?php

namespace Tests\Action;

use BpDailyMenu\AppBuilder;
use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\PDOFactory;
use BpDailyMenu\RestaurantCatalog;
use DateInterval;
use DatePeriod;
use DateTime;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\Helper\WebTestCase;

class IntervalMenuActionTest extends TestCase {

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

//    /**
//     * @test
//     */
//    public function invoke_givenFromAndToDates_containsAllDatesBetweenFromAndToDates() {
//        list($from, $to) = array('2018-01-01', '2018-01-10');
//        $interval = $this->generateInterval($from, $to);
//
//        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/interval', null, "from=$from&to=$to");
//        $this->assertEquals(200, $response->getStatusCode());
//        foreach ($interval as $date) {
//            $this->assertContains($date->format('Y-m-d'), (string) $response->getBody());
//        }
//    }

    /**
     * @test
     */
    public function invoke_noMenuForGivenIntervalForAllRestaurants_notContainsRestaurantsData() {
        list($from, $to) = array('2018-02-01', '2018-02-10');

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/interval', null, "from=$from&to=$to");
        $this->assertEquals(200, $response->getStatusCode());

        $restaurants = RestaurantCatalog::getAll();
        $this->responseNotContainsRestaurantData($response, $restaurants);
    }

    /**
     * @test
     */
    public function invoke_thereIsMenuForAllDaysOfGivenIntervalForAllRestaurants_containsRestaurantDataForEveryRestaurantAndMenuForAllRestaurantForAllDays() {
        list($from, $to) = array('2018-03-03', '2018-03-11');
        $interval = $this->generateInterval($from, $to);

        $restaurants = RestaurantCatalog::getAll();
        $intervalMenus = [];
        foreach ($interval as $date) {
            $menus = $this->createMenusFromDateAndRestaurantKeys($date->format('Y-m-d'), array_keys($restaurants));
            $this->insertMenus($menus);
            $intervalMenus[] = $menus;
        }

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/interval',null, "from=$from&to=$to");
        $this->assertEquals(200, $response->getStatusCode());

        $this->responseContainsRestaurantData($response, $restaurants);
        foreach ($intervalMenus as $menus) {
            $this->responseContainsMenuData($response, $menus);
        }
    }

    /**
     * @test
     */
    public function invoke_thereIsMenuForSomeDaysOfGivenInterval_containsDateOnlyForDaysWhenThereIsMenuAndRestaurantDataAndMenuForOnlyRestaurantsThatHasMenu() {
        list($from, $to) = array('2018-03-03', '2018-03-04');

        $restaurants = RestaurantCatalog::getAll();
        $this->assertGreaterThan(1, count($restaurants));
        $oneRestaurant = array_slice($restaurants, 0, 1);
        $restRestaurants = array_slice($restaurants, 1);

        $menu = $this->createMenu($from, 1000, 'Test menu', array_keys($oneRestaurant)[0]);
        $this->insertMenus([$menu]);

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/interval',null, "from=$from&to=$to");
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertContains($from, (string) $response->getBody());
        $this->assertNotContains($to, (string) $response->getBody());

        $this->responseContainsRestaurantData($response, $oneRestaurant);
        $this->responseContainsMenuData($response, [$menu]);
        $this->responseNotContainsRestaurantData($response, $restRestaurants);
    }

    /**
     * @test
     */
    public function invoke_thereIsMenuOutsideGivenInterval_notContainsMenuOutside() {
        list($from, $to) = array('2018-03-03', '2018-03-04');

        $restaurants = RestaurantCatalog::getAll();
        $oneRestaurant = array_slice($restaurants, 0, 1);

        $insertedDate = '2018-03-02';
        $menu = $this->createMenu($insertedDate, 1000, 'Test menu', array_keys($oneRestaurant)[0]);
        $this->insertMenus([$menu]);

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/interval',null, "from=$from&to=$to");
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertNotContains($insertedDate, (string) $response->getBody());
        $this->responseNotContainsMenuData($response, [$menu]);
        $this->responseNotContainsRestaurantData($response, $oneRestaurant);
    }

    private function generateInterval(string $from, string $to): DatePeriod {
        $fromDate = new DateTime($from);
        $toDate = new DateTime($to);
        $toDate->modify('+1 day');
        return new DatePeriod($fromDate, new DateInterval('P1D'), $toDate);
    }

    private function responseContainsRestaurantData(ResponseInterface $response, array $restaurants) {
        foreach ($restaurants as $restaurant) {
            $this->assertContains($restaurant['name'], (string) $response->getBody());
            $this->assertContains($restaurant['map_url'], (string) $response->getBody());
        }
    }

    private function responseNotContainsRestaurantData(ResponseInterface $response, array $restaurants) {
        foreach ($restaurants as $restaurant) {
            $this->assertNotContains($restaurant['name'], (string) $response->getBody());
            $this->assertNotContains($restaurant['map_url'], (string) $response->getBody());
        }
    }

    private function responseContainsMenuData(ResponseInterface $response, array $menus) {
        foreach ($menus as $menu) {
            $this->assertContains((string) $menu['price'], (string) $response->getBody());
            $this->assertContains($menu['menu'], (string) $response->getBody());
        }
    }

    private function responseNotContainsMenuData(ResponseInterface $response, array $menus) {
        foreach ($menus as $menu) {
            $this->assertNotContains((string) $menu['price'], (string) $response->getBody());
            $this->assertNotContains($menu['menu'], (string) $response->getBody());
        }
    }

    private function createMenusFromDateAndRestaurantKeys(string $date, array $restaurantKeys): array {
        $menus = [];
        for ($i = 0; $i < count($restaurantKeys); $i++) {
            $menus[] = $this->createMenu($date, 1000 + $i, "Test menu $date $i", $restaurantKeys[$i]);
        }
        return $menus;
    }

    private function createMenu(string $date, int $price, string $menu, string $restaurant): array {
        return [
            'date' => $date,
            'price' => $price,
            'menu' => $menu,
            'restaurant' => $restaurant
        ];
    }

    private function insertMenus(array $menus) {
        foreach ($menus as $menu) {
            $statement = self::$pdo->prepare("INSERT INTO daily_menu (restaurant, date, price, menu) VALUES (:restaurant, :date, :price, :menu)");
            $statement->execute($menu);
        }
    }
}