<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Slim\App;
use BpDailyMenu\AppBuilder;

class AppBuilderTest extends TestCase {

    /**
     * @test
     */
    public function invoke_returnsApp() {
        $app = (new AppBuilder)();
        $this->assertInstanceOf(App::class, $app);
    }
}