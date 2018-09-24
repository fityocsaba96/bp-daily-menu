<?php

use Phinx\Seed\AbstractSeed;

class SeedBonnieTable extends AbstractSeed {

    public function run(): void {
        $this->table('bonnie')
             ->insert([
                 [
                     'date' => '2018-09-24',
                     'price' => 1350,
                     'soup' => 'Májgaluskaleves',
                     'dish' => 'Roston csirkemell paradicsommal, mozzarellával és párolt rizzsel'
                 ],
                 [
                     'date' => '2018-09-25',
                     'price' => 1350,
                     'soup' => 'Magyaros gombaleves',
                     'dish' => 'Juhtúróval töltött sertéskaraj rántva, kevert salátával'
                 ]
             ])
             ->save();
    }
}