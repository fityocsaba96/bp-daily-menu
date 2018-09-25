<?php

use Phinx\Migration\AbstractMigration;

class CreateDailyMenuTable extends AbstractMigration {

    public function change(): void {
        $this->table('daily_menu')
             ->addColumn('restaurant', 'string', ['limit' => 100])
             ->addColumn('date', 'date')
             ->addColumn('price', 'integer')
             ->addColumn('menu', 'text')
             ->create();
    }
}
