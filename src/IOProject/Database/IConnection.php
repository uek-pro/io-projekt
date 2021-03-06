<?php namespace IOProject\Database;

interface IConnection {
    
    public static function prepareDatabase();
    public function connectDatabase();
    public function createTablesIfNotExists();
    public function dropTables();
    public function checkLogonData($login, $pass);
    public function isUserExists($login);
    public function addUser($login, $pass);
    public function addEmployee($userId, $employee);
    public function getUsersEmployees($userId);
    public function getEmployeeById($employeeId);
    public function changeEmployee($employee);
    public function delEmployee($employeeId);
}