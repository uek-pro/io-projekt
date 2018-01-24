<?php namespace IOProject;

use IOProject\Database\SQLiteConnection;
use IOProject\Management\Employee;
use IOProject\Accountancy\ContractTaxCalculationService;

class IOProjectApi {

    public function getEvent() {

        $event = filter_input(INPUT_GET, 'a');
        if ($event == 'logon') {
            $this->logInUser();
            return 1;
        } else if ($event == 'logout') {
            $this->logoutUser();
            return 2;
        } else if ($event == 'register') {
            $this->registerUser();
            return 3;
        } else if ($event == 'add-employee') {
            $this->addEmployee();
            return 4;
        } else if ($event == 'edit-employee') {
            $this->editEmployee();
            return 5;
        } else if ($event == 'del-employee') {
            $this->delEmployee();
            return 6;
        }
        return null;
    }

    private function logInUser() {

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
    }

    private function logoutUser() {

        if (isset($_SESSION['logged_id'])) {
            unset($_SESSION['logged_id']);
            unset($_SESSION['logged_user']);
        }
    }

    private function registerUser() {

        $login = filter_input(INPUT_POST, 'login-register');
        $password = filter_input(INPUT_POST, 'password-register');
        $password2 = filter_input(INPUT_POST, 'password-repeat-register');

        if (!empty($password) && !empty($login)) {
            if ($password == $password2) {

                $database = SQLiteConnection::prepareDatabase();
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
    }

    private function addEmployee() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $employeeId = filter_input(INPUT_POST, 'id');
    
            $forename = filter_input(INPUT_POST, 'forename');
            $surname = filter_input(INPUT_POST, 'surname');
            $grossSalary = filter_input(INPUT_POST, 'gross-salary');
            $contractType = filter_input(INPUT_POST, 'contract-type');
    
            if (!empty($forename) && !empty($surname) && !empty($grossSalary)) {
    
                $taxCalculation = new ContractTaxCalculationService();
                $result = $taxCalculation->calculateAll($grossSalary, $contractType);
    
                $employee = new Employee(
                    $forename,
                    $surname,
                    filter_input(INPUT_POST, 'pesel'),
                    filter_input(INPUT_POST, 'account-number'),
                    $contractType,
                    round($result->getNetSalary(), 2),
                    $grossSalary,
                    round($result->getCostOfEmployer(), 2),
                    $employeeId
                );
    
                $database = SQLiteConnection::prepareDatabase();
                if ($employeeId == null)
                    $database->addEmployee($_SESSION['logged_id'], $employee);
                else 
                    $database->changeEmployee($employee);
            } else {
                if ($employeeId == null) {
                    $_SESSION['employee_add_attempt'] = true;
                    $_SESSION['employee_page'] = true;
                }
            }
    
        } else $_SESSION['employee_page'] = true;
    }

    private function editEmployee() {

        $employeeId = filter_input(INPUT_GET, 'id');
        if ($employeeId != null) {
            $database = SQLiteConnection::prepareDatabase();
            $employee = $database->getEmployeeById($employeeId);
            
            if ($employee != null) {
                $_SESSION['employee_page'] = true;
                $_SESSION['employee_changing'] = $employee;
            }
        }
    }

    private function delEmployee() {

        $employeeId = filter_input(INPUT_GET, 'id');
        if ($employeeId != null) {
            $database = SQLiteConnection::prepareDatabase();
            $database->delEmployee($employeeId);
        }
    }

    public function getEmployeesDetailsAsync() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $employeeId = filter_input(INPUT_POST, 'employee-id');
        
            if ($employeeId && is_numeric($employeeId)) {
                
                $database = SQLiteConnection::prepareDatabase();
                $employee = $database->getEmployeeById($employeeId);
        
                $taxCalculation = new ContractTaxCalculationService();
                $result = $taxCalculation->calculateAll($employee->getGrossSalary(), $employee->getContractType());
        
                if ($employee != null) {
                    $response['calculation'] = $result;
                    $response['employee'] = $employee;
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                }
                
                return json_encode($response);
            }
        }
        return null;
    }
}