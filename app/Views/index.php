<?= $this->extend("layout/template") ?>

<?= $this->section("content") ?>

<?php
    $table = new \CodeIgniter\View\Table();
    $table->setHeading("Název", "Datum");

    /**@var array $zavod */

    foreach($zavod as $row){
        $datum = $row->start_date . ' - ' . $row->end_date;
        $table->addRow($row->real_name, $datum);
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