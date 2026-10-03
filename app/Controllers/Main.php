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
        ->select('race_year.id, race_year.id_race, race_year.real_name, race_year.start_date, race_year.end_date')
        ->where('race_year.id_race', $id)
        ->orderBy('race_year.year', 'desc')
        ->findAll();

        $this->data = [
            'zavod' => $zavod
        ];

        echo view('index', $this->data);
    }

    public function etapy($id){
        $etapy = $this->raceYear
        ->select('stage.date, stage.distance, stage.vertical_meters, parcour_type.name, rider.first_name, rider.last_name, result.team_link')
        ->join('stage', 'race_year.id = stage.id_race_year', 'inner')
        ->join('parcour_type', 'stage.parcour_type = parcour_type.id', 'inner')
        ->join('result', 'stage.id = result.id_stage', 'inner')
        ->join('rider', 'result.id_rider = rider.id', 'left') // změna na left join kvůli id_rider = 0 v TTT
        ->where('race_year.id', $id)
        ->where('result.rank', 1)
        ->groupStart() // začátek skupiny podmínek
            ->where('result.type_result', 1) // klasická etapa
            ->orWhere('result.type_result', 3) // časovka družstev
        ->groupEnd()
        ->orderBy('stage.number', 'asc')
        ->findAll();

        $this->data = [
            'etapy' => $etapy
        ];

        echo view('etapy', $this->data);
    }
}
