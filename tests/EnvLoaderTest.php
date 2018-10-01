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
        $this->assertNotFalse(getenv('DB_NAME'));
        $this->assertNotFalse(getenv('DB_USER'));
        $this->assertNotFalse(getenv('DB_PASS'));
    }

    /**
     * @test
     */
    public function invoke_envFileNotExists_doesntLoadEnvVars() {
        $envLoader = new EnvLoader();
        $dbName = getenv('DB_NAME');
        putenv('DB_NAME');
        $this->assertFalse(getenv('DB_NAME'));
        $envLoader('production');
        $this->assertFalse(getenv('DB_NAME'));
        putenv("DB_NAME=$dbName");
    }
}