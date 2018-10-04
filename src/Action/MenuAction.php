<?php

namespace BpDailyMenu\Action;

use BpDailyMenu\Dao\DailyMenuDao;
use BpDailyMenu\RestaurantCatalog;
use BpDailyMenu\Validator\DateIntervalValidator;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class MenuAction {

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
        if (!$from && !$to)
            return $this->renderToday($response);
        else
            return $this->renderInterval($response, $from, $to);
    }

    private function renderToday(Response $response): ResponseInterface {
        $menus = $this->groupMenusByDateAndRestaurant($this->dao->getDailyMenu());
        return $this->renderMenus($response, $menus, false);
    }

    private function renderInterval(Response $response, string $from, string $to): ResponseInterface {
        $error = (new DateIntervalValidator)($from, $to);
        if (!$error) {
            $menus = $this->groupMenusByDateAndRestaurant($this->dao->getMenusBetweenInterval($from, $to));
            return $this->renderMenus($response, $menus, true);
        } else
            return $response->write($error)->withStatus(400);
    }

    private function renderMenus(Response $response, array $menus, bool $fillForm): ResponseInterface {
        return $this->view->render($response, 'menu.html.twig', [
            'restaurants' => RestaurantCatalog::getAll(),
            'menus_of_dates' => $this->explodeMenusByNewLine($menus),
            'fill_form' => $fillForm
        ]);
    }

    private function groupMenusByDateAndRestaurant(array $menus): array {
        $groupedMenus = [];
        foreach ($menus as $menu) {
            list($date, $restaurant) = [$menu['date'], $menu['restaurant']];
            unset($menu['date'], $menu['restaurant']);
            $groupedMenus[$date][$restaurant][] = $menu;
        }
        return $groupedMenus;
    }

    private function explodeMenusByNewLine(array $menusOfDates): array {
        foreach ($menusOfDates as &$menusOfRestaurants) {
            foreach ($menusOfRestaurants as &$menus) {
                foreach ($menus as &$menu) {
                    $menu['menu'] = explode(PHP_EOL, $menu['menu']);
                }
            }
        }
        return $menusOfDates;
    }
}