<?php

namespace BpDailyMenu;

use BpDailyMenu\Action\HealthCheckAction;
use BpDailyMenu\Dao\HealthCheckDao;
use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class AppBuilder {

    /**
     * @var App
     */
    private $app;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __invoke(): App {
        (new EnvLoader)();
        $this->app = new App();
        $this->container = $this->app->getContainer();

        $this->container[PDO::class] = (new PDOFactory)->createWithDBName();
        $this->container[HealthCheckDao::class] = new HealthCheckDao($this->container[PDO::class]);
        $this->app->get('/healthcheck', HealthCheckAction::class);
        $this->container[HealthCheckAction::class] = function ($container) {
            return new HealthCheckAction($container[HealthCheckDao::class], $container[Twig::class]);
        };

        $this->setupTwig();

        return $this->app;
    }

    private function setupTwig(): void {
        $this->container[Twig::class] = function (ContainerInterface $container) {
            $view = new Twig(__DIR__ . '/../templates');
            $basePath = rtrim(str_ireplace(__DIR__ . '/index.php', '',
                $container->get('request')->getUri()->getBasePath()), '/');
            $view->addExtension(new TwigExtension($container->get('router'), $basePath));
            return $view;
        };
    }
}