<?php

use Phinx\Seed\AbstractSeed;

class SeedMuzikumTable extends AbstractSeed {

    public function run(): void {
        $this->table('muzikum')
             ->insert([
                 [
                     'date' => '2018-09-24',
                     'price' => 1390,
                     'soup' => 'Francia hagymaleves diós veknivel',
                     'dish' => 'Csirkemell sajttal, sonkával sütve, petrezselymes burgonyával'
                 ],
                 [
                     'date' => '2018-09-25',
                     'price' => 1390,
                     'soup' => 'Burgonyás kelleves kéksajtos tejföllel',
                     'dish' => 'Háromborsos sertésborda tejfölös jalapeno-val, jázmin rizzsel'
                 ]
             ])
             ->save();
    }
}