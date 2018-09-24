<?php

namespace Tests\Dao;

use BpDailyMenu\Dao\KajahuDao;
use BpDailyMenu\PDOFactory;
use PDO;
use PHPUnit\Framework\TestCase;

class KajahuDaoTest extends TestCase {

    /**
     * @var PDO
     */
    private static $pdo;
    /**
     * @var KajahuDao
     */
    private static $dao;

    public static function setUpBeforeClass(): void {
        self::$pdo = (new PDOFactory)->createWithDBName();
        self::$dao = new KajahuDao(self::$pdo);
    }

    protected function setUp(): void {
        self::$pdo->query('TRUNCATE TABLE kajahu');
    }

    /**
     * @test
     */
    public function getDailyMenu_noMenuForToday_returnsEmptyArray() {
        $this->assertEquals([], self::$dao->getDailyMenu());
    }
}