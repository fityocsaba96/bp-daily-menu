<?php

namespace Tests\Action;

use BpDailyMenu\PDOFactory;
use PDO;
use PHPUnit\Framework\TestCase;
use Tests\Helper\WebTestCase;
use BpDailyMenu\AppBuilder;

class HealthCheckActionTest extends TestCase {

    use WebTestCase;

    /**
     * @test
     */
    public function invoke_containsAppOk() {
        $response = $this->runApp((new AppBuilder)(), 'GET', '/healthcheck');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('App OK', (string) $response->getBody());
    }

    /**
     * @test
     */
    public function invoke_containsDBOk() {
        $response = $this->runApp((new AppBuilder)(), 'GET', '/healthcheck');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('DB OK', (string) $response->getBody());
    }

    /**
     * @test
     */
    public function invoke_noDatabaseSelected_notContainsDBOk() {
        $app = (new AppBuilder)();
        $app->getContainer()[PDO::class] = (new PDOFactory)->createWithoutDBName();
        $response = $this->runApp($app, 'GET', '/healthcheck');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotContains('DB OK', (string) $response->getBody());
    }
}