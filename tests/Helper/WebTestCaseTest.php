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

    /**
     * @test
     */
    public function runApp_givenRequestBody_canHandleRequestBody() {
        $app = new App();
        $app->post('/test', function(Request $request, Response $response) {
            $body = $request->getParsedBody();
            $response->write($body['message']);
        });
        $requestData = [
            'message' => 'text'
        ];
        $response = $this->runApp($app, 'POST', '/test', $requestData);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text', (string) $response->getBody());
        $response = $this->runApp($app, 'POST', '/test');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('', (string) $response->getBody());
    }

    /**
     * @test
     */
    public function runApp_givenQueryString_canHandleQueryParams() {
        $app = new App();
        $app->post('/test', function(Request $request, Response $response) {
            $body = $request->getQueryParam('sad');
            $response->write($body);
        });
        $response = $this->runApp($app, 'POST', '/test', [], 'sad=gfh');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('gfh', (string) $response->getBody());
        $response = $this->runApp($app, 'POST', '/test', []);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('', (string) $response->getBody());
    }
}