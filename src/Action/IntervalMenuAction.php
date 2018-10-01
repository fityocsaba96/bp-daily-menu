<?php

namespace BpDailyMenu\Action;

use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\RestaurantCatalog;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class IntervalMenuAction {

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
        list($from, $to) = array($request->getQueryParam('from'), $request->getQueryParam('to'));

        return $this->view->render($response, 'interval_menu.html.twig', [
            'restaurants' => RestaurantCatalog::getAll(),
            'menus_of_interval' => $this->explodeMenusByNewLine($this->dao->getMenusBetweenInterval($from, $to))
        ]);
    }

    private function explodeMenusByNewLine(array $menusOfInterval): array {
        foreach ($menusOfInterval as &$menus) {
            foreach ($menus as &$menu) {
                $menu['menu'] = explode("\n", $menu['menu']);
            }
        }
        return $menusOfInterval;
    }
}