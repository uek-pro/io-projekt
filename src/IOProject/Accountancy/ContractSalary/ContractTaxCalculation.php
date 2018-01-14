<?php namespace IOProject\Accountancy\ContractSalary;

use IOProject\Core\Common;
use IOProject\Accountancy\Tax;
use IOProject\Accountancy\Enums\TaxType;
use IOProject\Accountancy\Enums\TaxValueType;

abstract class ContractTaxCalculation implements \JsonSerializable {

    protected $netSalary;
    protected $grossSalary;
    protected $costOfEmployer;
    protected $aTax = array();

    protected function __construct() {
        $this->aTax['income-tax'] = new Tax(18, TaxValueType::Percentile, 'Podatek dochodowy');
    }

    abstract function calculateGrossToNet($grossSalary);
    abstract function calculateGrossToCostOfEmployer($grossSalary);

    public function getNetSalary() {
        return $this->netSalary;
    }

    public function getGrossSalary() {
        return $this->grossSalary;
    }

    public function getCostOfEmployer() {
        return $this->costOfEmployer;
    }

    public function getTaxes() {
        return $this->aTax;
    }

    public function jsonSerialize() {
        return [
            'netSalary' => $this->netSalary,
            'grossSalary' => $this->grossSalary,
            'costOfEmployer' => $this->costOfEmployer,
            'taxes' => $this->aTax
        ];
    }
}