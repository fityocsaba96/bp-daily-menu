<?php

namespace Tests\Dao;

use PHPUnit\Framework\TestCase;
use BpDailyMenu\Dao\HealthCheckDao;
use BpDailyMenu\PDOFactory;

class HealthDaoTest extends TestCase {

    /**
     * @test
     */
    public function getDatabaseName_databaseIsSelected_returnsDbName() {
        $healthDao = new HealthCheckDao((new PDOFactory())->createWithDBName());
        $selectedDb = $healthDao->getDatabaseName();
        $this->assertEquals('bp_daily_menu_test', $selectedDb);
    }

    /**
     * @test
     */
    public function getDatabaseName_databaseIsNotSelected_returnsNull() {
        $healthDao = new HealthCheckDao((new PDOFactory())->createWithoutDBName());
        $selectedDb = $healthDao->getDatabaseName();
        $this->assertNull($selectedDb);
    }
}