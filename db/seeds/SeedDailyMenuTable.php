<?php

use Phinx\Seed\AbstractSeed;

class SeedDailyMenuTable extends AbstractSeed {

    public function run(): void {
        $this->table('daily_menu')
            ->insert([
                [
                    'restaurant' => 'kajahu',
                    'date' => '2018-09-24',
                    'price' => 1390,
                    'menu' => "Húsleves\nCarbonara spaghetti\nCsokoládés pohárkrém"
                ],
                [
                    'restaurant' => 'kajahu',
                    'date' => '2018-09-25',
                    'price' => 1390,
                    'menu' => "Bazsalikomos paradicsomleves\nBaconos csirkemell jázminrizzsel\nSport szelet"
                ],
                [
                    'restaurant' => 'bonnie',
                    'date' => '2018-09-24',
                    'price' => 1350,
                    'menu' => "Májgaluskaleves\nRoston csirkemell paradicsommal, mozzarellával és párolt rizzsel"
                ],
                [
                    'restaurant' => 'bonnie',
                    'date' => '2018-09-25',
                    'price' => 1350,
                    'menu' => "Magyaros gombaleves\nJuhtúróval töltött sertéskaraj rántva, kevert salátával"
                ],
                [
                    'restaurant' => 'muzikum',
                    'date' => '2018-09-24',
                    'price' => 1390,
                    'menu' => "Francia hagymaleves diós veknivel\nCsirkemell sajttal, sonkával sütve, petrezselymes burgonyával"
                ],
                [
                    'restaurant' => 'muzikum',
                    'date' => '2018-09-25',
                    'price' => 1390,
                    'menu' => "Burgonyás kelleves kéksajtos tejföllel\nHáromborsos sertésborda tejfölös jalapeno-val, jázmin rizzsel"
                ],
                [
                    'restaurant' => 'vendiak',
                    'date' => '2018-09-24',
                    'price' => 1590,
                    'menu' => "Házi tea\nKarfiolleves\nCsirkepaprikás szarvacskával"
                ],
                [
                    'restaurant' => 'vendiak',
                    'date' => '2018-09-25',
                    'price' => 1590,
                    'menu' => "Házi tea\nParadicsomleves betűtésztával\nCigánypecsenye hasábburgonyával"
                ]
            ])
            ->save();
    }
}
