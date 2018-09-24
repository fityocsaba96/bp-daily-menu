<?php

namespace BpDailyMenu;

use BpDailyMenu\Action\HealthCheckAction;
use BpDailyMenu\Dao\HealthCheckDao;
use BpDailyMenu\Dao\RestaurantDao;
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

        $this->setup();
        return $this->app;
    }

    private function setup(): void {
        $this->setupPDO();
        $this->setupDaos();
        $this->setupRoutes();
        $this->setupTwig();
        $this->setupDependencies();
    }

    private function setupPDO(): void {
        $this->container[PDO::class] = (new PDOFactory)->createWithDBName();
    }

    private function setupDaos(): void {
        $this->container[HealthCheckDao::class] = function ($container) {
            return new HealthCheckDao($container[PDO::class]);
        };

        $this->container[RestaurantDao::class] = function ($container) {
            return new RestaurantDao($container[PDO::class]);
        };
    }

    private function setupRoutes(): void {
        $this->app->get('/healthcheck', HealthCheckAction::class);
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

    private function setupDependencies(): void {
        $this->container[HealthCheckAction::class] = function ($container) {
            return new HealthCheckAction($container[HealthCheckDao::class], $container[Twig::class]);
        };
    }
}