<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use BpDailyMenu\PDOFactory;

class PDOFactoryTest extends TestCase {

    /**
     * @test
     */
    public function createWithoutDBName_returnsPDOBasedOnEnvVar() {
        $pdoFactory = new PDOFactory();
        $pdo = $pdoFactory->createWithoutDBName();
        $database = $pdo->query('SELECT DATABASE()')->fetchColumn();
        $this->assertInstanceOf(PDO::class, $pdo);
        $this->assertNull($database);
    }

    /**
     * @test
     */
    public function createWithDBName_returnsPDOBasedOnEnvVar() {
        $pdoFactory = new PDOFactory();
        $pdo = $pdoFactory->createWithDBName();
        $database = $pdo->query('SELECT DATABASE()')->fetchColumn();
        $this->assertInstanceOf(PDO::class, $pdo);
        $this->assertNotNull($database);
    }
}