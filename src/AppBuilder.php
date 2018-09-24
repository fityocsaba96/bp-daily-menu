<?php

namespace BpDailyMenu;

use Slim\App;

class AppBuilder {

    public function __invoke(): App {
        (new EnvLoader)();
        return new App();
    }
}