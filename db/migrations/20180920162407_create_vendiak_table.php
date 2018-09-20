<?php

use Phinx\Migration\AbstractMigration;

class CreateVendiakTable extends AbstractMigration {

    public function change(): void {
        $this->table('vendiak')
             ->addColumn('date', 'date')
             ->addColumn('price', 'integer')
             ->addColumn('soup', 'string', ['limit' => 100])
             ->addColumn('dish', 'string', ['limit' => 100])
             ->addColumn('drink', 'string', ['limit' => 100])
             ->create();
    }
}
