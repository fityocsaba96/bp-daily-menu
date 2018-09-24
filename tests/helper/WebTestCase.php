<?php

namespace Tests\Helper;

use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

trait WebTestCase {

    private function runApp(App $app, string $method, string $endpoint): ResponseInterface {
        $environment = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $endpoint
        ]);
        $request = Request::createFromEnvironment($environment);
        return $app($request, new Response());
    }
}