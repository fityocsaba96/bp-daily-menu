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
}