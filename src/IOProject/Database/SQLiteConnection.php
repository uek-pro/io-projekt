<?php namespace IOProject\Database;

use \PDO;

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
}