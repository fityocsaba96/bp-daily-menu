<?php

use Phinx\Migration\AbstractMigration;

class CreateRestaurantsTable extends AbstractMigration {

    public function change(): void {
        $this->table('restaurant')
             ->addColumn('name', 'string', ['limit' => 50])
             ->addColumn('menu_url', 'text')
             ->addColumn('map_url', 'text')
             ->create();
    }
}
