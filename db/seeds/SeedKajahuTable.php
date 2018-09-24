<?php

use Phinx\Seed\AbstractSeed;

class SeedKajahuTable extends AbstractSeed {

    public function run(): void {
        $this->table('kajahu')
             ->insert([
                 [
                     'date' => '2018-09-24',
                     'price' => 1390,
                     'soup' => 'Húsleves',
                     'dish' => 'Carbonara spaghetti',
                     'dessert' => 'Csokoládés pohárkrém'
                 ],
                 [
                     'date' => '2018-09-25',
                     'price' => 1390,
                     'soup' => 'Bazsalikomos paradicsomleves',
                     'dish' => 'Baconos csirkemell jázminrizzsel',
                     'dessert' => 'Sport szelet'
                 ]
             ])
             ->save();
    }
}