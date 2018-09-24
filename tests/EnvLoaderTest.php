<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use BpDailyMenu\EnvLoader;

class EnvLoaderTest extends TestCase {

    /**
     * @test
     */
    public function invoke_loadsEnvVarsBasedOnApplicationEnv() {
        $envLoader = new EnvLoader();
        $envLoader();
        $this->assertEquals('bp_daily_menu_test', getenv('DB_NAME'));
        $this->assertEquals('academy', getenv('DB_USER'));
        $this->assertEquals('academy', getenv('DB_PASS'));
    }

    /**
     * @test
     */
    public function invoke_envFileNotExists_doesntLoadEnvVars() {
        $envLoader = new EnvLoader();
        $envLoader('production');
        $this->expectNotToPerformAssertions();
    }
}