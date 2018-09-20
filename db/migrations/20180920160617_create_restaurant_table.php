<?php

use Phinx\Migration\AbstractMigration;

class CreateRestaurantTable extends AbstractMigration {

    public function change(): void {
        $this->table('restaurant')
             ->addColumn('table_name', 'string', ['limit' => 50])
             ->addColumn('name', 'string', ['limit' => 50])
             ->addColumn('menu_url', 'text')
             ->addColumn('map_url', 'text')
             ->create();
    }
}
