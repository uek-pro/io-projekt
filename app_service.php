<?php

require_once __DIR__ . '/vendor/autoload.php';

use IOProject\Database\SQLiteConnection;
use IOProject\View;

header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $employeeId = filter_input(INPUT_POST, 'employee-id');

    if ($employeeId && is_numeric($employeeId)) {
        
        $database = SQLiteConnection::prepareDatabase();
        $employee = $database->getEmployeeById($employeeId);
        
        if ($employee != null) {
            $response['employee'] = $employee;
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }
        
        echo json_encode($response);
    }
}