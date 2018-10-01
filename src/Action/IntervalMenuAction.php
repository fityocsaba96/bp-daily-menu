<?php

namespace BpDailyMenu\Action;

use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\RestaurantCatalog;
use BpDailyMenu\Validator\DateIntervalValidator;
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
        $error = (new DateIntervalValidator)($from, $to);
        if (!$error) {
            return $this->view->render($response, 'interval_menu.html.twig', [
                'restaurants' => RestaurantCatalog::getAll(),
                'menus_of_interval' => $this->explodeMenusByNewLine($this->dao->getMenusBetweenInterval($from, $to))
            ]);
        } else {
            return $response->write($error)->withStatus(400);
        }
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