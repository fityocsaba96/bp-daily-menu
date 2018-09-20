<?php

use Phinx\Migration\AbstractMigration;

class CreateMuzikumTable extends AbstractMigration {

    public function change(): void {
        $this->table('muzikum')
             ->addColumn('date', 'date')
             ->addColumn('price', 'integer')
             ->addColumn('soup', 'string', ['limit' => 100])
             ->addColumn('dish', 'string', ['limit' => 100])
             ->create();
    }
}
