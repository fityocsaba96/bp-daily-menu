<?php

namespace Tests\Helper;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class WebTestCaseTest extends TestCase {

    use WebTestCase;

    /**
     * @test
     */
    public function runApp_givenExistingEndpointAndMethod_returnsValidResponse() {
        $app = new App();
        $app->get('/test', function(Request $request, Response $response) {
            $response->write('Ok');
        });
        $response = $this->runApp($app, 'GET', '/test');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Ok', (string) $response->getBody());
    }
}