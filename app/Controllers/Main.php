<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\RaceYear;

class Main extends BaseController
{
    private object $raceYear;
    private array $data;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->raceYear = new RaceYear();
    }

    public function index(){
        $id = 124;

        $zavod = $this->raceYear
        ->select('race_year.id_race, race_year.real_name, race_year.start_date, race_year.end_date')
        ->where('race_year.id_race', $id)
        ->orderBy('race_year.year', 'desc')
        ->findAll();

        $this->data = [
            'zavod' => $zavod
        ];

        echo view('index', $this->data);
    }
}
