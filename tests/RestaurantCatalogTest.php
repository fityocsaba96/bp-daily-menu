<?php

namespace Tests;

use BpDailyMenu\RestaurantCatalog;
use PHPUnit\Framework\TestCase;

class RestaurantCatalogTest extends TestCase {

    /**
     * @test
     */
    public function getAll_returnsNotEmptyArrayWithCorrectKeys() {
        $restaurants = RestaurantCatalog::getAll();
        $this->assertInternalType('array', $restaurants);
        $this->assertNotEmpty($restaurants);
        foreach ($restaurants as $restaurant) {
            $this->assertArrayHasKey('name', $restaurant);
            $this->assertArrayHasKey('menu_url', $restaurant);
            $this->assertArrayHasKey('map_url', $restaurant);
        }
    }

    /**
     * @test
     */
    public function get_givenExistingKey_returnsExistingRestaurant() {
        $restaurants = RestaurantCatalog::getAll();
        $restaurantKeys = array_keys($restaurants);
        foreach ($restaurantKeys as $restaurantKey) {
            $restaurant = RestaurantCatalog::get($restaurantKey);
            $this->assertContains($restaurant, $restaurants);
        }
    }
}