<?php

require_once __DIR__ . '/vendor/autoload.php';

use IOProject\IOProjectApi;

session_start();

$api = new IOProjectApi();
$api->getEvent();

ob_start();

require_once __DIR__ . '/template/_header.php';

if (isset($_SESSION['logged_id']))
    if (isset($_SESSION['employee_page'])) {
        unset($_SESSION['employee_page']);
        require_once __DIR__ . '/template/employee.php';
        unset($_SESSION['employee_changing']);
    } else require_once __DIR__ . '/template/dashboard.php';
else require_once __DIR__ . '/template/mainpage.php';

require_once __DIR__ . '/template/_footer.php';

ob_end_flush();