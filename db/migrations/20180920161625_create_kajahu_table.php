<?php

use Phinx\Migration\AbstractMigration;

class CreateKajahuTable extends AbstractMigration {

    public function change(): void {
        $this->table('kajahu')
             ->addColumn('date', 'date')
             ->addColumn('price', 'integer')
             ->addColumn('soup', 'string', ['limit' => 100])
             ->addColumn('dish', 'string', ['limit' => 100])
             ->addColumn('dessert', 'string', ['limit' => 100])
             ->create();
    }
}
