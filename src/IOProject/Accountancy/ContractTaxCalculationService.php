<?php namespace IOProject\Accountancy;

use IOProject\Accountancy\Enums\ContractType;
use IOProject\Accountancy\ContractSalary\EmploymentContract\EmploymentContractTaxCalculation;
use IOProject\Accountancy\ContractSalary\MandatoryContract\MandatoryContractTaxCalculation;
use IOProject\Accountancy\ContractSalary\SpecificTaskContract\SpecificTaskContractTaxCalculation;

class ContractTaxCalculationService {

    public function calculateAll($grossSalary, $contractType) {

        $taxCalculation = $this->getAlgorithmByContractType($contractType);
        if ($taxCalculation != null) {
            $taxCalculation->calculateGrossToNet($grossSalary);
            $taxCalculation->calculateGrossToCostOfEmployer($grossSalary);
            return $taxCalculation;
        }
    }

    public function calculateNet($grossSalary, $contractType) {

        $taxCalculation = $this->getAlgorithmByContractType($contractType);
        if ($taxCalculation != null) {
            $taxCalculation->calculateGrossToNet($grossSalary);
            return $taxCalculation;
        }
    }

    public function calculaterCostOfEmployer($grossSalary, $contractType) {

        $taxCalculation = $this->getAlgorithmByContractType($contractType);
        if ($taxCalculation != null) {
            $taxCalculation->calculateGrossToCostOfEmployer($grossSalary);
            return $taxCalculation;
        }
    }

    private function getAlgorithmByContractType($contractType) {

        switch ($contractType) {
            case ContractType::EmploymentContract:
                $taxCalculation = new EmploymentContractTaxCalculation();
                break;
            case ContractType::MandatoryContract:
                $taxCalculation = new MandatoryContractTaxCalculation();
                break;
            case ContractType::SpecificTaskContract:
                $taxCalculation = new SpecificTaskContractTaxCalculation();
                break;
            default:
                return null;
        }
        return $taxCalculation;
    }
}