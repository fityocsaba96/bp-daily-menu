<?php

namespace BpDailyMenu;

use Dotenv\Dotenv;

class EnvLoader {

    public function __invoke(string $env = null): void {
        $env = $env ?? getenv('APPLICATION_ENV');
        $envFile = '.env.' . $env;
        $path = __DIR__ . '/../';
        if (is_file($path . $envFile)) {
            $env = new Dotenv($path, $envFile);
            $env->overload();
        }
    }
}