<?php namespace IOProject\Database;

use \PDO;
use IOProject\Management\Employee;

class SQLiteConnection implements IConnection {

    const DATABASE_NAME = "database_name.sqlite";
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
            'SELECT forename, surname, pesel, account_number, contract_type, net_salary, gross_salary, cost_of_employer
            FROM employees INNER JOIN users ON users.user_id = employees.user_id
            WHERE users.user_id = :user_id'
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
                    $row['cost_of_employer']
                );
            }
            return $employees;
        }
        else
            return false; // NOTE: null będzie lepszy? count(false) == 1
    }
}