<?php

namespace BpDailyMenu\Action;

use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\RestaurantCatalog;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class TodaysMenuAction {

    /**
     * @var DailyMenuDao
     */
    private $dao;
    /**
     * @var Twig
     */
    private $view;

    public function __construct(DailyMenuDao $dao, Twig $view) {
        $this->dao = $dao;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface {
        return $this->view->render($response, 'daily_menu.html.twig', [
            'date' => date('Y-m-d'),
            'restaurants' => RestaurantCatalog::getAll(),
            'menus' => $this->explodeMenusByNewLine($this->dao->getDailyMenu())
        ]);
    }

    private function explodeMenusByNewLine(array $menus): array {
        foreach ($menus as &$menu) {
            $menu['menu'] = explode("\n", $menu['menu']);
        }
        return $menus;
    }
}