<?php

namespace BpDailyMenu\Action;

use BpDailyMenu\Dao\BonnieDao;
use BpDailyMenu\Dao\KajahuDao;
use BpDailyMenu\Dao\MuzikumDao;
use BpDailyMenu\Dao\RestaurantDao;
use BpDailyMenu\Dao\VendiakDao;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class TodaysMenuAction {

    /**
     * @var RestaurantDao
     */
    private $restaurantDao;
    /**
     * @var KajahuDao
     */
    private $kajahuDao;
    /**
     * @var BonnieDao
     */
    private $bonnieDao;
    /**
     * @var MuzikumDao
     */
    private $muzikumDao;
    /**
     * @var VendiakDao
     */
    private $vendiakDao;
    /**
     * @var Twig
     */
    private $view;

    public function __construct(RestaurantDao $restaurantDao, KajahuDao $kajahuDao, BonnieDao $bonnieDao,
                                MuzikumDao $muzikumDao, VendiakDao $vendiakDao, Twig $view) {
        $this->restaurantDao = $restaurantDao;
        $this->kajahuDao = $kajahuDao;
        $this->bonnieDao = $bonnieDao;
        $this->muzikumDao = $muzikumDao;
        $this->vendiakDao = $vendiakDao;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface {
        return $this->view->render($response, 'daily_menu.html.twig', [
            'restaurants' => $this->restaurantDao->list()
        ]);
    }
}