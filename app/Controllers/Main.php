<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\RaceYear;
use App\Models\Stage;
use App\Models\Result;
use App\Models\Race;

class Main extends BaseController
{
    private object $raceYear;
    private object $stage;
    private object $result;
    private object $race;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->raceYear = new RaceYear();
        $this->stage = new Stage();
        $this->result = new Result();
        $this->race = new Race();
    }

    public function index(){
        $id = 124;
        $posledniZaznam = 12283;

        $zavod = $this->race
        ->select('race_year.id, race_year.id_race, race_year.real_name, race_year.start_date, race_year.end_date')
        ->join('race_year', 'race.id = race_year.id_race', 'inner')
        ->where('race.id', $id)
        ->orWhere('race_year.id >', $posledniZaznam)
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
        ->select('result.rank, result.team_link, rider.first_name, rider.last_name, result.time, result.note')
        ->join('rider', 'result.id_rider = rider.id', 'left')
        ->where('result.id_stage', $id_stage)
        ->where('result.type_result', $type_result)
        ->orderBy('rank', 'asc')
        ->findAll();

        $vysledkySCasem = [];
        $vysledkyBezCasu = [];

        foreach($poradi as $row){
            if ($row->time == null || $row->time == '00:00:00') {
                $vysledkyBezCasu[] = $row;
            }
            else {
                $vysledkySCasem[] = $row;
            }
        }

        $finalniPoradi = array_merge($vysledkySCasem, $vysledkyBezCasu);

        $this->data = [
            'poradi' => $finalniPoradi
        ];

        echo view('poradi', $this->data);
    }

    function pridat(){
        $zavody = $this->race
        ->join('race_year', 'race.id = race_year.id_race', 'inner')
        ->where('race_year.category', 'E')
        ->where('race_year.sex', 'M')
        ->distinct()
        ->orderBy('race.id', 'asc')
        ->findAll();

        $dropdown = ['default' => 'Vyberte závod'];
        foreach($zavody as $zavod){
            $dropdown[$zavod->id] = $zavod->default_name . ' (' . $zavod->country . ')';
        }

        $this->data += [
            'zavody' => $dropdown
        ];

        echo view('formular', $this->data);
    }

    function vytvorit(){
        $logo = $this->request->getFile('logo');
        $real_name = $this->request->getPost('real_name');
        $id_race = $this->request->getPost('id_race');

        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        
        $year = !empty($start_date) ? substr($start_date, 0, 4) : 2026;

        $uploadKnihovna = new \App\Libraries\FileUpload();
        $uploadLogo = $uploadKnihovna->uploadFile($logo, 'logos/', 'logo_' . time());

        if ($uploadLogo['uploaded']) { 
            $data = array(
                'logo' => $uploadLogo['name'],
                'real_name' => $real_name,
                'id_race' => $id_race,
                'category' => 'E',
                'sex' => 'M',
                'start_date' => $start_date,
                'end_date' => $end_date,
                'year' => $year
            );

            $alertKnihovna = new \App\Libraries\Alert();
            $vysledek = $this->raceYear->save($data);

            $alert = $alertKnihovna->makeMessage($vysledek, 'dbAdd');
            session()->setFlashdata('alert', $alert);

            return redirect()->route('/');
        }
        else {
            return redirect()->route('/');
        }

    }
}
