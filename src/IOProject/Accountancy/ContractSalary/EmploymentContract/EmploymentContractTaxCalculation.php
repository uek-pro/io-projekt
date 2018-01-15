<?php namespace IOProject\Accountancy\ContractSalary\EmploymentContract;

use IOProject\Core\Common;
use IOProject\Accountancy\Tax;
use IOProject\Accountancy\Enums\TaxValueType;

class EmploymentContractTaxCalculation extends \IOProject\Accountancy\ContractSalary\ContractTaxCalculation {

    public function __construct() {
        parent::__construct();
        // pracodawca
        $this->aTax['pension-insurance']->setValue(9.76);
        $this->aTax['disability-insurance']->setValue(6.5);
        $this->aTax['accident-insurance']->setValue(1.8);
        $this->aTax['labor-fund']->setValue(2.45);
        $this->aTax['gebf']->setValue(0.1);

        $this->aTax['income-cost'] = new Tax('Koszt uzyskania przychodu', TaxValueType::Constant, 111.25);
        $this->aTax['tax-relief'] = new Tax('Ulga podatkowa', TaxValueType::Constant, 46.33);
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

        $advanceBase = round($base - $this->aTax['income-cost']->getOutcome());

        $this->aTax['income-tax']->cal($advanceBase);

        $advance = round($this->aTax['income-tax']->getOutcome() - $this->aTax['tax-relief']->getOutcome() - $this->aTax['health-insurance-deduction']->getOutcome());
        $this->aTax['advance']->setOutcome($advance > 0 ? $advance : 0);

        $this->netSalary = $base - $this->aTax['advance']->getOutcome() - $this->aTax['health-insurance']->getOutcome();    
    }

    public function calculateGrossToCostOfEmployer($grossSalary) {
        $this->grossSalary = $grossSalary;

        $this->aTax['pension-insurance']->cal($this->grossSalary);
        $this->aTax['disability-insurance']->cal($this->grossSalary);
        $this->aTax['accident-insurance']->cal($this->grossSalary);
        $this->aTax['labor-fund']->cal($this->grossSalary);
        $this->aTax['gebf']->cal($this->grossSalary);

        $this->costOfEmployer = $this->grossSalary + $this->aTax['pension-insurance']->getOutcome() + $this->aTax['disability-insurance']->getOutcome() + $this->aTax['accident-insurance']->getOutcome() + $this->aTax['labor-fund']->getOutcome() + $this->aTax['gebf']->getOutcome();
    }
}

// 20,61%