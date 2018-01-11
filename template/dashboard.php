<?php 

use IOProject\Database\SQLiteConnection;
use IOProject\View;

$database = SQLiteConnection::prepareDatabase();
$employees = $database->getUsersEmployees($_SESSION['logged_id']);

?>

<div class="header">
    <div class="logo">
        <a href="">
            <object class="logo" type="image/svg+xml" data="template/images/logo.svg">
                Logo
                <!-- fallback image in CSS -->
            </object>
        </a>
    </div>
    <h1>IOProject - Menadżer pracowników</h1>
    <p>zalogowany jako: <span><?= $_SESSION['logged_user'] ?></span> | <a href="index.php?a=logout">wyloguj</a>
</div>

<div class="main">

    <h2>Lista pracowników<?= View::generateCurrentEmployeesCount($employees) ?></h2>

    <a class="button" href="index.php?a=add-employee">Zatrudnij nowego pracownika</a>

    <?= View::generateEmployeesList($employees) ?>

    <?= View::generateTotalEmployeesCost($employees); ?>

</div>

<div class="details">

    <h2>Szczegóły o pracowniku</h2>

    <a class="button" href="index.php?a=edit-employee&id=1">Edytuj pracownika</a>
    <a class="button" href="index.php?a=del-employee&id=1">Zwolnij pracownika</a>

    <dl>
        <dt>Wynagrodzenie netto</dt><dd>5020 zł</dd>
        <dt>Wynagrodzenie brutto</dt><dd>5800 zł</dd>
        <dt>Koszt pracownika</dt><dd>6600 zł</dd>
        <dt>...</dt><dd>...</dd>
    <dl>

</div>

<script src="template\show-details.js"></script>