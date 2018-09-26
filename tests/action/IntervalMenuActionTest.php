<?php

namespace Tests\Action;

use BpDailyMenu\AppBuilder;
use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\PDOFactory;
use DateInterval;
use DatePeriod;
use DateTime;
use PDO;
use PHPUnit\Framework\TestCase;
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

    /**
     * @test
     */
    public function invoke_givenFromAndToDates_containsAllDatesBetweenFromAndToDates() {
        list($from, $to) = array('2018-01-01', '2018-01-10');
        $fromDate = new DateTime($from);
        $toDate = new DateTime($to);
        $toDate->modify('+1 day');
        $interval = new DatePeriod($fromDate, new DateInterval('P1D'), $toDate);

        $response = $this->runApp((new AppBuilder)(), 'GET', '/menu/interval', null, "from=$from&to=$to");
        $this->assertEquals(200, $response->getStatusCode());
        foreach ($interval as $date) {
            $this->assertContains($date->format('Y-m-d'), (string) $response->getBody());
        }
    }
}