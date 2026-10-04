<?php

namespace App\Config;

class Alert
{
    public $alertMessage = [
        'dbAddError' => 'Záznam se nepřidal',
        'dbEditError' => 'Záznam se neaktulizoval',
        'dbDelError' => 'Záznam se nesmazal',
        'dbAddSuccess' => 'Záznam byl přidán do databáze',
        'dbEditSuccess' => 'Záznam se aktualizoval',
        'dbDelSuccess' => 'Záznam byl smazán'
    ];
}