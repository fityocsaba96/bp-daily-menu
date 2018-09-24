<?php

use Phinx\Seed\AbstractSeed;

class SeedVendiakTable extends AbstractSeed {

    public function run(): void {
        $this->table('vendiak')
             ->insert([
                 [
                     'date' => '2018-09-24',
                     'price' => 1590,
                     'soup' => 'Karfiolleves',
                     'dish' => 'Csirkepaprikás szarvacskával',
                     'drink' => 'Házi tea'
                 ],
                 [
                     'date' => '2018-09-25',
                     'price' => 1590,
                     'soup' => 'Paradicsomleves betűtésztával',
                     'dish' => 'Cigánypecsenye hasábburgonyával',
                     'drink' => 'Házi tea'
                 ]
             ])
             ->save();
    }
}