<?= $this->extend("layout/template") ?>

<?= $this->section("content") ?>

<h1 class="text-center m-4">Přidat ročník závodu</h1>

<?php
    $dataLogo = array(
        'type' => 'file',
        'name' => 'logo',
        'id' => 'logo',
        'class' => 'form-control',
        'accept' => '.jpg, .jpeg, .png, .svg',
        'required' => 'required'
    );

    $dataName = array(
        'name' => 'real_name',
        'id' => 'real_name',
        'class' => 'form-control',
        'required' => 'required',
        'placeholder' => 'Vložte název ročníku'
    );

    $dataLabel = array(
        'class' => 'form-label'
    );

    $dataBtn = array(
        'type' => 'submit',
        'class' => 'btn btn-primary mt-3',
        'content' => 'Uložit ročník'
    );

    $dataDateStart = array(
        'type' => 'date', // Přepnuto na typ date, aby uživatel viděl kalendář
        'name' => 'start_date',
        'id' => 'start_date',
        'class' => 'form-control',
        'required' => 'required'
    );

    $dataDateEnd = array(
        'type' => 'date', // Přepnuto na typ date, aby uživatel viděl kalendář
        'name' => 'end_date',
        'id' => 'end_date',
        'class' => 'form-control',
        'required' => 'required'
    );

    $atributyRace = 'id="id_race" class="form-select" required="required"';

    echo form_open_multipart('polozka/vytvorit');
?>

<div class="form-floating mt-2 mb-2">
    <?= form_input($dataLogo); ?>
    <?= form_label('Logo závodu', 'logo', $dataLabel); ?>
</div>

<div class="form-floating mt-2 mb-2">
    <?= form_input($dataName); ?>
    <?= form_label('Název ročníku (real_name)', 'real_name', $dataLabel); ?>
</div>

<div class="form-floating mt-2 mb-2">
    <?= form_input($dataDateStart); ?>
    <?= form_label('Start datum', 'start_date', $dataLabel); ?>
</div>

<div class="form-floating mt-2 mb-2">
    <?= form_input($dataDateEnd); ?>
    <?= form_label('End datum', 'end_date', $dataLabel); ?>
</div>


<?php /**@var array $zavody */ ?>

<div class="form-floating mt-2 mb-2">
    <?= form_dropdown('id_race', $zavody, 'default', $atributyRace); ?>
    <?= form_label('Výběr závodu (Pouze mužské elitní kategorie E)', 'id_race', $dataLabel); ?>
</div>

<?= form_button($dataBtn); ?>

<?= form_close() ?>

<?= $this->endSection() ?>