<?php

use Phinx\Seed\AbstractSeed;

class SeedRestaurantTable extends AbstractSeed {

    public function run(): void {
        $this->table('restaurant')
             ->insert([
                 [
                     'table_name' => 'kajahu',
                     'name' => 'Kajahu',
                     'menu_url' => 'https://appif.kajahu.com/jdmenu?jseat=-&jlang=hu',
                     'map_url' => 'https://goo.gl/maps/wgjZGzc3P822'
                 ],
                 [
                     'table_name' => 'bonnie',
                     'name' => 'Bonnie',
                     'menu_url' => 'http://bonnierestro.hu/hu/napimenu',
                     'map_url' => 'https://goo.gl/maps/WG2dHwT9AeG2'
                 ],
                 [
                     'table_name' => 'muzikum',
                     'name' => 'Muzikum',
                     'menu_url' => 'http://muzikum.hu/heti-menu',
                     'map_url' => 'https://goo.gl/maps/aSMrvAeLu6x'
                 ],
                 [
                     'table_name' => 'vendiak',
                     'name' => 'Véndiák',
                     'menu_url' => 'http://www.vendiaketterem.hu/heti_ajanlat',
                     'map_url' => 'https://goo.gl/maps/qqNxAny89rN2'
                 ]
             ])
             ->save();
    }
}