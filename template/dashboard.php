<?php 

use IOProject\Core\Config;
use IOProject\Database\SQLiteConnection;
use IOProject\View;

$database = SQLiteConnection::prepareDatabase();
$employees = $database->getUsersEmployees($_SESSION['logged_id']);

?>

<div class="container dashboard">

    <div class="header">
        <div class="logo">
            <a href="">
                <object class="logo" type="image/svg+xml" data="template/images/logo.svg">
                    Logo
                    <!-- fallback image in CSS -->
                </object>
            </a>
        </div>
        <div class="title">
            <h1><?= Config::APPLICATION_TITLE ?></h1>
            <p>zalogowany jako: <span><?= $_SESSION['logged_user'] ?></span> | <a href="index.php?a=logout">wyloguj</a>
        </div>
    </div>

    <div class="main">

        <h2>Lista pracowników<?= View::generateCurrentEmployeesCount($employees) ?></h2>

        <a class="button green" href="index.php?a=add-employee">Zatrudnij nowego pracownika</a>

        <?= View::generateEmployeesList($employees) ?>

        <?= View::generateTotalEmployeesCost($employees); ?>

    </div>

    <div id="details"></div>

</div>

<script>const consts = <?= json_encode(array('currency' => Config::CURRENCY)) ?></script>
<script src="template\show-details.js"></script>