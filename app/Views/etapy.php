<?= $this->extend("layout/template") ?>

<?= $this->section("content") ?>

<?php
    $table = new \CodeIgniter\View\Table();
    $table->setHeading("Datum", "Délka", "Převýšení", "Typ", "Vítěz", "Pořadí");

    /**@var array $etapy */

    foreach($etapy as $row){
        $typEtapy = 0;
        $vitez = '';

        if (!empty($row->first_name)) {
            $typEtapy = 1;
            $vitez = $row->first_name . " " . $row->last_name;
        }
        else {
            $typEtapy = 3;
            $teamBezLomitka = basename($row->team_link); // vrátí znaky na konci po '/'
            $teamBezRoku = substr($teamBezLomitka, 0, -5);
            $teamBezSpojovniku = str_replace('-', ' ', $teamBezRoku);
            $vitez = ucwords($teamBezSpojovniku) . " (TTT)"; // převede první písmena slov na velké
        }

        $odkazVEtape = anchor('poradi/' . $row->id . '/' . $typEtapy, 'V etapě');
        $odkazPoEtape = anchor('poradi/' . $row->id . '/4', 'Po etapě');

        $table->addRow(
            $row->date, 
            $row->distance . " km", 
            $row->vertical_meters . " m", 
            $row->name, 
            $vitez,
            $odkazVEtape . " | " . $odkazPoEtape
        );
    }

    $template = array(
        'table_open'=> '<table class="table table-bordered table-hover">',
        'thead_open'=> '<thead class="table-dark">',
        'thead_close'=> '</thead>',
        'heading_row_start'=> '<tr>',
        'heading_row_end'=>' </tr>',
        'heading_cell_start'=> '<th>',
        'heading_cell_end' => '</th>',
        'tbody_open' => '<tbody>',
        'tbody_close' => '</tbody>',
        'row_start' => '<tr>',
        'row_end'  => '</tr>',
        'cell_start' => '<td>',
        'cell_end' => '</td>',
        'row_alt_start' => '<tr>',
        'row_alt_end' => '</tr>',
        'cell_alt_start' => '<td>',
        'cell_alt_end' => '</td>',
        'table_close' => '</table>',
    );

    $table->setTemplate($template);
    echo $table->generate();
?>

<?= $this->endSection() ?>