<?php

require_once __DIR__ . '/vendor/autoload.php';

use IOProject\IOProjectApi;
use IOProject\Database\SQLiteConnection;

session_start();

$api = new IOProjectApi();

$event = $api->getEvent();
if ($event == 1) {

    // TODO: sprawdziać czy zostają przekazywane parametry metodą post
    $login = filter_input(INPUT_POST, 'login');
    $password = filter_input(INPUT_POST, 'password');

    $database = SQLiteConnection::prepareDatabase();
    if ($data = $database->checkLogonData($login, $password)) {
        $_SESSION['logged_user'] = $data['login'];
    } else {
        $_SESSION['logon_bad_attempt'] = true;
    }

} else if ($event == 2) {

    if (isset($_SESSION['logged_user']))
        unset($_SESSION['logged_user']);

} else if ($event == 3) {

    $login = filter_input(INPUT_POST, 'login-register');
    $password = filter_input(INPUT_POST, 'password-register');
    $password2 = filter_input(INPUT_POST, 'password-repeat-register');

    $database = SQLiteConnection::prepareDatabase();

    if (!empty($password)) {
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
        $_SESSION['register_attempt'] = 'Hasło nie może być puste';
    }
}

ob_start();

require_once __DIR__ . '/template/_header.php';

isset($_SESSION['logged_user']) ? require_once __DIR__ . '/template/dashboard.php' : require_once __DIR__ . '/template/mainpage.php';

require_once __DIR__ . '/template/_footer.php';

ob_end_flush();