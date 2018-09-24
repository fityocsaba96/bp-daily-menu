<?php

namespace BpDailyMenu\Action;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use BpDailyMenu\Dao\HealthCheckDao;

class HealthCheckAction {

    /**
     * @var HealthCheckDao
     */
    private $dao;
    /**
     * @var Twig
     */
    private $view;

    public function __construct(HealthCheckDao $dao, Twig $view) {
        $this->dao = $dao;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface {
        return $this->view->render($response, 'healthcheck.html.twig');
    }
}