<?php

namespace Tests\Helper;

use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

trait WebTestCase {

    private function runApp(App $app, string $method, string $endpoint, array $data = null): ResponseInterface {
        $environment = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $endpoint
        ]);
        $request = Request::createFromEnvironment($environment);
        if ($data) {
            $request = $request->withParsedBody($data);
        }
        return $app($request, new Response());
    }
}