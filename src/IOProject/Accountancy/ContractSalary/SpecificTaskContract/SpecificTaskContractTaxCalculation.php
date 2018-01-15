<?php namespace IOProject\Accountancy\ContractSalary\SpecificTaskContract;

use IOProject\Core\Common;
use IOProject\Accountancy\Tax;
use IOProject\Accountancy\Enums\TaxValueType;

class SpecificTaskContractTaxCalculation extends \IOProject\Accountancy\ContractSalary\ContractTaxCalculation {

    public function __construct() {
        parent::__construct();
        $this->aTax['income-cost'] = new Tax('Koszt uzyskania przychodu', TaxValueType::Percentile, 50);
    }
    
    public function calculateGrossToNet($grossSalary) {
        $this->grossSalary = $grossSalary;

        $this->aTax['pension-contribution']->cal($this->grossSalary);
        $this->aTax['disability-contribution']->cal($this->grossSalary);
        $this->aTax['sickness-contribution']->cal($this->grossSalary);
        
        $socialSecurity = $this->aTax['pension-contribution']->getOutcome() + $this->aTax['disability-contribution']->getOutcome() + $this->aTax['sickness-contribution']->getOutcome();

        $base = $this->grossSalary - $socialSecurity;

        $this->aTax['health-insurance']->cal($base);
        $this->aTax['health-insurance-deduction']->cal($base);
        
        $this->aTax['income-cost']->cal($base);

        $advanceBase = round($base - $this->aTax['income-cost']->getOutcome());

        $this->aTax['income-tax']->cal($advanceBase);

        $advance = round($this->aTax['income-tax']->getOutcome() - $this->aTax['health-insurance-deduction']->getOutcome());
        $this->aTax['advance']->setOutcome($advance > 0 ? $advance : 0);

        $this->netSalary = $base - $this->aTax['advance']->getOutcome() - $this->aTax['health-insurance']->getOutcome();   
    }

    public function calculateGrossToCostOfEmployer($grossSalary) {
        $this->grossSalary = $grossSalary;
        $this->costOfEmployer = $grossSalary;
    }
}