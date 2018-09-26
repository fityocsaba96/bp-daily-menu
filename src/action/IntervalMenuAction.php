<?php

namespace BpDailyMenu\Action;

use BpDailyMenu\Dao\DailyMenuDao;
use DateInterval;
use DatePeriod;
use DateTime;
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
            'intervalMenu' => $this->generateIntervalMenu($from, $to)
        ]);
    }

    private function generateIntervalMenu(string $from, string $to): array {
        $intervalMenu = [];
        $interval = $this->generateInterval($from, $to);
        foreach ($interval as $date) {
            $intervalMenu[] = $this->generateMenu($date);
        }
        return $intervalMenu;
    }

    private function generateInterval(string $from, string $to): DatePeriod {
        $fromDate = new DateTime($from);
        $toDate = new DateTime($to);
        $toDate->modify('+1 day');
        return new DatePeriod($fromDate, new DateInterval('P1D'), $toDate);
    }

    private function generateMenu(DateTime $date): array {
        return [
            'date' => $date->format('Y-m-d')
        ];
    }
}