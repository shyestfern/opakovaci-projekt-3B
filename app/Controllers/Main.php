<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\RaceYear;
use App\Models\Stage;
use App\Models\Result;

class Main extends BaseController
{
    private object $raceYear;
    private object $stage;
    private object $result;
    private array $data;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->raceYear = new RaceYear();
        $this->stage = new Stage();
        $this->result = new Result();
    }

    public function index(){
        $id = 124;

        $zavod = $this->raceYear
        ->select('race_year.id, race_year.id_race, race_year.real_name, race_year.start_date, race_year.end_date')
        ->where('race_year.id_race', $id)
        ->orderBy('race_year.year', 'desc')
        ->findAll();

        foreach($zavod as $row){
            $etapyRocniku = $this->stage
            ->where('stage.id_race_year', $row->id)
            ->findAll();

            $celkovaDelka = 0;
            foreach($etapyRocniku as $etapa){
                $celkovaDelka += $etapa->distance;
            }

            $row->total_distance = round($celkovaDelka);
        }

        $this->data = [
            'zavod' => $zavod
        ];

        echo view('index', $this->data);
    }

    public function etapy($id){
        $etapy = $this->raceYear
        ->select('stage.id, stage.date, stage.distance, stage.vertical_meters, parcour_type.name, rider.first_name, rider.last_name, result.team_link')
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

    public function poradi($id_stage, $type_result){
        $poradi = $this->result
        ->select('result.rank, result.team_link, rider.first_name, rider.last_name, result.time')
        ->join('rider', 'result.id_rider = rider.id', 'left')
        ->where('result.id_stage', $id_stage)
        ->where('result.type_result', $type_result)
        ->orderBy('rank', 'asc')
        ->findAll();

        $this->data = [
            'poradi' => $poradi
        ];

        echo view('poradi', $this->data);
    }
}
