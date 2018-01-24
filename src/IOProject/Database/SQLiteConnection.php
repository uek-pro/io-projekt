<?php namespace IOProject\Database;

use \PDO;
use IOProject\Core\Config;
use IOProject\Management\Employee;

class SQLiteConnection implements IConnection {

    const DATABASE_NAME = Config::DATABASE_NAME;
    private $pdo;

    public static function prepareDatabase() {
        $db = new SQLiteConnection();
        $db->connectDatabase();
        $db->createTablesIfNotExists();
        return $db;
    }

    public function connectDatabase() {
        try {
            $this->pdo = new PDO("sqlite:" . self::DATABASE_NAME);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }

    public function createTablesIfNotExists() { // NOTE: pasuje połączyć relacją i dodać metodę usuwania rekordów
        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS users (
            user_id INTEGER PRIMARY KEY,
            login TEXT,
            pass TEXT
            )"
        );
        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS employees (
            employee_id INTEGER PRIMARY KEY,
            user_id INTEGER,
            forename TEXT,
            surname TEXT,
            pesel TEXT,
            account_number TEXT,
            contract_type INTEGER,
            net_salary REAL,
            gross_salary REAL,
            cost_of_employer REAL
            )"
        );
    }
    
    public function dropTables() {
        $this->pdo->exec("DROP TABLE users");
        $this->pdo->exec("DROP TABLE employees");
    }

    public function checkLogonData($login, $pass) {
        $query = $this->pdo->prepare(
            'SELECT user_id, login, pass FROM users WHERE login = :login AND pass = :pass LIMIT 1'
        );
        $query->bindValue(':login', $login, PDO::PARAM_STR);
        $query->bindValue(':pass', $pass, PDO::PARAM_STR);
        $query->execute();
        if ($row = $query->fetch(PDO::FETCH_ASSOC))
            return $row;
        return false;
    }

    public function isUserExists($login) {
        $query = $this->pdo->prepare(
            'SELECT user_id FROM users WHERE login = :login LIMIT 1'
        );
        $query->bindValue(':login', $login, PDO::PARAM_STR);
        $query->execute();
        if ($row = $query->fetch(PDO::FETCH_ASSOC))
            return true;
        else
            return false;
    }

    public function addUser($login, $pass) {
        $query = $this->pdo->prepare(
            'INSERT INTO users (login, pass) VALUES (:login, :pass)'
        );
        $query->bindValue(':login', $login, PDO::PARAM_STR);
        $query->bindValue(':pass', $pass, PDO::PARAM_STR);
        if ($query->execute())
            return true;
        else
            return false;
    }

    public function addEmployee($userId, $employee) {
        $query = $this->pdo->prepare(
            'INSERT INTO employees (user_id, forename, surname, pesel, account_number, contract_type, net_salary, gross_salary, cost_of_employer)
            VALUES (:user_id, :forename, :surname, :pesel, :account_number, :contract_type, :net_salary, :gross_salary, :cost_of_employer)'
        );
        $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $query->bindValue(':forename', $employee->getForename(), PDO::PARAM_STR);
        $query->bindValue(':surname', $employee->getSurname(), PDO::PARAM_STR);
        $query->bindValue(':pesel', $employee->getPESEL(), PDO::PARAM_STR);
        $query->bindValue(':account_number', $employee->getAccountNumber(), PDO::PARAM_STR);
        $query->bindValue(':contract_type', $employee->getContractType(), PDO::PARAM_INT);
        $query->bindValue(':net_salary', $employee->getNetSalary(), PDO::PARAM_STR);
        $query->bindValue(':gross_salary', $employee->getGrossSalary(), PDO::PARAM_STR);
        $query->bindValue(':cost_of_employer', $employee->getCostOfEmployer(), PDO::PARAM_STR);
        if ($query->execute())
            return true;//$this->pdo->lastInsertId();
        else
            return false;
    }

    public function getUsersEmployees($userId) {
        $query = $this->pdo->prepare(
            'SELECT employee_id, forename, surname, pesel, account_number, contract_type, net_salary, gross_salary, cost_of_employer
            FROM employees INNER JOIN users ON users.user_id = employees.user_id
            WHERE users.user_id = :user_id ORDER BY surname ASC'
        );
        $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) > 0) {
            $employees = array();
            foreach($rows as $row) {

                $employees[] = new Employee(
                    $row['forename'],
                    $row['surname'],
                    $row['pesel'],
                    $row['account_number'],
                    $row['contract_type'],
                    $row['net_salary'],
                    $row['gross_salary'],
                    $row['cost_of_employer'],
                    $row['employee_id']
                );
            }
            return $employees;
        }
        else
            return null;
    }

    public function getEmployeeById($employeeId) { // NOTE: brak zabezpieczenia hasłem
        $query = $this->pdo->prepare(
            'SELECT * FROM employees WHERE employee_id = :id LIMIT 1'
        );
        $query->bindValue(':id', $employeeId, PDO::PARAM_INT);
        $query->execute();
        if ($row = $query->fetch(PDO::FETCH_ASSOC))
            return new Employee(
                $row['forename'],
                $row['surname'],
                $row['pesel'],
                $row['account_number'],
                $row['contract_type'],
                $row['net_salary'],
                $row['gross_salary'],
                $row['cost_of_employer'],
                $row['employee_id']
            );
        else
            return null;
    }

    public function changeEmployee($employee) {

        $query = $this->pdo->prepare(
            'UPDATE employees SET forename = :forename,
            surname = :surname,
            pesel = :pesel,
            account_number = :account_number,
            contract_type = :contract_type,
            net_salary = :net_salary,
            gross_salary = :gross_salary,
            cost_of_employer = :cost_of_employer
            WHERE employee_id = :employee_id'
        );
        $query->bindValue(':employee_id', $employee->getId(), PDO::PARAM_INT);
        $query->bindValue(':forename', $employee->getForename(), PDO::PARAM_STR);
        $query->bindValue(':surname', $employee->getSurname(), PDO::PARAM_STR);
        $query->bindValue(':pesel', $employee->getPESEL(), PDO::PARAM_STR);
        $query->bindValue(':account_number', $employee->getAccountNumber(), PDO::PARAM_STR);
        $query->bindValue(':contract_type', $employee->getContractType(), PDO::PARAM_INT);
        $query->bindValue(':net_salary', $employee->getNetSalary(), PDO::PARAM_STR);
        $query->bindValue(':gross_salary', $employee->getGrossSalary(), PDO::PARAM_STR);
        $query->bindValue(':cost_of_employer', $employee->getCostOfEmployer(), PDO::PARAM_STR);
        if ($query->execute() && $query->rowCount() > 0)
            return true;
        else
            return false;
    }

    public function delEmployee($employeeId) {

        $query = $this->pdo->prepare(
            'DELETE FROM employees WHERE employee_id = :id'
        );
        $query->bindValue(':id', $employeeId, PDO::PARAM_INT);
        $query->execute();
        if ($query->rowCount() > 0)
            return true;
        else
            return false;
    }
}