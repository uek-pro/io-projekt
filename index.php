<?php

require_once __DIR__ . '/vendor/autoload.php';

use IOProject\IOProjectApi;
use IOProject\Database\SQLiteConnection;
use IOProject\Management\Employee;

session_start();

$api = new IOProjectApi();

$event = $api->getEvent();
if ($event == 1) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $login = filter_input(INPUT_POST, 'login');
        $password = filter_input(INPUT_POST, 'password');

        $database = SQLiteConnection::prepareDatabase();
        if ($data = $database->checkLogonData($login, $password)) {
            $_SESSION['logged_id'] = $data['user_id'];
            $_SESSION['logged_user'] = $data['login'];
        } else {
            $_SESSION['logon_bad_attempt'] = true;
        }
    }

} else if ($event == 2) {

    if (isset($_SESSION['logged_id'])) {
        unset($_SESSION['logged_id']);
        unset($_SESSION['logged_user']);
    }
        

} else if ($event == 3) {

    $login = filter_input(INPUT_POST, 'login-register');
    $password = filter_input(INPUT_POST, 'password-register');
    $password2 = filter_input(INPUT_POST, 'password-repeat-register');

    $database = SQLiteConnection::prepareDatabase();

    if (!empty($password) && !empty($login)) {
        if ($password == $password2) {
            if (!$database->isUserExists($login)) {
                if ($database->addUser($login, $password)) {
                    $_SESSION['register_attempt'] = 'Zarejestrowano pomyślnie';
                } else {
                    $_SESSION['register_attempt'] = 'Coś poszło nie tak';
                }
            } else {
                $_SESSION['register_attempt'] = 'Istnieje użytkownik z takim loginem';
            }
        } else {
            $_SESSION['register_attempt'] = 'Hasła nie zgadzają się';
        }
    } else {
        $_SESSION['register_attempt'] = 'Pola nie mogą być puste';
    }

} else if ($event == 4) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $database = SQLiteConnection::prepareDatabase();

    $employee = new Employee(
        filter_input(INPUT_POST, 'forename'),
        filter_input(INPUT_POST, 'surname'),
        filter_input(INPUT_POST, 'pesel'),
        filter_input(INPUT_POST, 'account-number'),
        filter_input(INPUT_POST, 'contract-type'),
        filter_input(INPUT_POST, 'net-salary'),
        filter_input(INPUT_POST, 'gross-salary'),
        filter_input(INPUT_POST, 'cost-of-employer')
    );
    // NOTE: nie ma pól wymaganych

    $database->addEmployee($_SESSION['logged_id'], $employee);

    } else $_SESSION['employee_page'] = true;

} else if ($event == 5) {

} else if ($event == 6) {

    $employeeId = filter_input(INPUT_GET, 'id');
    if ($employeeId != null) {
        $database = SQLiteConnection::prepareDatabase();
        $database->delEmployee($employeeId);
    }
}

ob_start();

require_once __DIR__ . '/template/_header.php';

if (isset($_SESSION['logged_id']))
    if (isset($_SESSION['employee_page'])) {
        unset($_SESSION['employee_page']);
        require_once __DIR__ . '/template/employee.php';
    } else require_once __DIR__ . '/template/dashboard.php';
else require_once __DIR__ . '/template/mainpage.php';

require_once __DIR__ . '/template/_footer.php';

ob_end_flush();