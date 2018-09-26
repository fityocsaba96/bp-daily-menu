<?php

namespace BpDailyMenu;

class RestaurantCatalog {

    private static $restaurants = [
        'kajahu' => [
            'name' => 'Kajahu',
            'menu_url' => 'https://appif.kajahu.com/jdmenu?jseat=-&jlang=hu',
            'map_url' => 'https://goo.gl/maps/wgjZGzc3P822'
        ],
        'bonnie' => [
            'name' => 'Bonnie',
            'menu_url' => 'http://bonnierestro.hu/hu/napimenu',
            'map_url' => 'https://goo.gl/maps/WG2dHwT9AeG2'
        ],
        'muzikum' => [
            'name' => 'Muzikum',
            'menu_url' => 'http://muzikum.hu/heti-menu',
            'map_url' => 'https://goo.gl/maps/aSMrvAeLu6x'
        ],
        'vendiak' => [
            'name' => 'Véndiák',
            'menu_url' => 'http://www.vendiaketterem.hu/heti_ajanlat',
            'map_url' => 'https://goo.gl/maps/qqNxAny89rN2'
        ]
    ];

    public static function getAll(): array {
        return self::$restaurants;
    }

    public static function get(string $key): array {
        return self::$restaurants[$key];
    }
}