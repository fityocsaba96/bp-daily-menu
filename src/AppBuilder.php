<?php

namespace BpDailyMenu;

use Slim\App;

class AppBuilder {

    public function __invoke(): App {
        return new App();
    }
}