<?php

namespace App\Libraries;

use App\Config\Alert as AlertConfig;

class Alert
{
    protected $config;

    public function __construct(){
        $this->config = new AlertConfig();
    }

    /**
    * @param $status - boolean podle toho, jestli byl success nebo error
    * @param $type - string, jaký typ operace proběhl, podle konfiguračního souboru - dbAdd, dbEdit, dbDel apod
    * @return - objekt se dvěma atributy - text hlášky v message a třída v class

    */
    public function makeMessage($status, $type) {
        $result = new \stdClass();
        if($status) {
            $result->class = "success";
            $shortType = $type."Success";
        } else {
            $result->class = "danger";
            $shortType = $type."Error";
        }
        $result->message = $this->config->alertMessage[$shortType];
        return $result;
    }
}