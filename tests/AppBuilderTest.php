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

    /**
     * @test
     */
    public function invoke_loadsEnvVars() {
        $app = (new AppBuilder)();
        $this->assertNotFalse(getenv('DB_NAME'));
    }
}