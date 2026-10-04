<?= $this->extend("layout/template") ?>

<?= $this->section("content") ?>

<?php
    $table = new \CodeIgniter\View\Table();
    $table->setHeading("Pořadí", "Jméno", "Čas");

    /**@var array $poradi */

    foreach($poradi as $row){
        $formatPoradi = '';

        if ($row->rank > 0) {
            $formatPoradi = $row->rank . ".";
        }
        else {
            $formatPoradi = '-';
        }

        $jmeno = '';

        if (!empty($row->first_name)) {
            $jmeno = $row->first_name . " " . $row->last_name;
        }
        else {
            $teamBezLomitka = basename($row->team_link); // vrátí znaky na konci po '/'
            $teamBezRoku = substr($teamBezLomitka, 0, -5);
            $teamBezSpojovniku = str_replace('-', ' ', $teamBezRoku);
            $jmeno = ucwords($teamBezSpojovniku); // převede první písmena slov na velké
        }

        $casNeboNote = '';

        if ($row->time != null && $row->time != '00:00:00') {
            $casNeboNote = $row->time;
        }
        else if (!empty($row->note)) {
            $casNeboNote = $row->note;
        }
        else {
            $casNeboNote = '-';
        }

        $table->addRow(
            $formatPoradi,
            $jmeno,
            $casNeboNote
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