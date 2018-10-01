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
        $date = date('Y-m-d');
        return $this->view->render($response, 'daily_menu.html.twig', [
            'date' => $date,
            'restaurants' => RestaurantCatalog::getAll(),
            'intervalMenus' => $this->explodeMenusByNewLine($this->dao->getDailyMenu())
        ]);
    }

    private function explodeMenusByNewLine(array $intervalMenus): array {
        foreach ($intervalMenus as &$date) {
            foreach ($date as &$menu) {
                $menu['menu'] = explode("\n", $menu['menu']);
            }
        }
        return $intervalMenus;
    }
}