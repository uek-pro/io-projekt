<?php

require_once __DIR__ . '/vendor/autoload.php';

use IOProject\IOProjectApi;

header("Content-Type: application/json; charset=utf-8");

$api = new IOProjectApi();
$details = $api->getEmployeesDetailsAsync();
if ($details != null)
    echo $details;