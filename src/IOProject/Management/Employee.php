<?php namespace IOProject\Management;

class Employee implements \JsonSerializable {

    private $id;
    private $forename;
    private $surname;
    private $PESEL;
    private $accountNumber;
    private $contractType;
    private $netSalary;
	private $grossSalary;
	private $costOfEmployer;

    public function __construct($forename, $surname, $pesel, $accountNumber, $contractType, $netSalary, $grossSalary, $costOfEmployer, $id = -1) {
        $this->id = $id;
        $this->forename = $forename;
        $this->surname = $surname;
        $this->PESEL = $pesel;
        $this->accountNumber = $accountNumber;
        $this->contractType = $contractType;
        $this->netSalary = $netSalary;
        $this->grossSalary = $grossSalary;
        $this->costOfEmployer = $costOfEmployer;
    }

    public function getId() {
        return $this->id;
    }

    public function getForename() {
        return $this->forename;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getPESEL() {
        return $this->PESEL;
    }

    public function getAccountNumber() {
        return $this->accountNumber;
    }

    public function getContractType() {
        return $this->contractType;
    }

    public function getNetSalary() {
        return $this->netSalary;
    }

    public function getGrossSalary() {
        return $this->grossSalary;
    }

    public function getCostOfEmployer() {
        return $this->costOfEmployer;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'forename' => $this->forename,
            'surname' => $this->surname,
            'PESEL' => $this->PESEL,
            'accountNumber' => $this->accountNumber,
            'contractType' => $this->contractType,
            'netSalary' => $this->netSalary,
            'grossSalary' => $this->grossSalary,
            'costOfEmployer' => $this->costOfEmployer
        ];
    }
}