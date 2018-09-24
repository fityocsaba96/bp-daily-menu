<?php

namespace Tests\Action;

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
}