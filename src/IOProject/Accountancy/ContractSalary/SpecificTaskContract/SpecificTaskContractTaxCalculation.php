<?php namespace IOProject\Accountancy\ContractSalary\SpecificTaskContract;

use IOProject\Core\Common;
use IOProject\Accountancy\Tax;
use IOProject\Accountancy\Enums\TaxValueType;

class SpecificTaskContractTaxCalculation extends \IOProject\Accountancy\ContractSalary\ContractTaxCalculation {

    public function __construct() {
        parent::__construct();
        
        // pracodawca
        $this->aTax['pension-insurance'] = new Tax(0, TaxValueType::Percentile, 'Ubezpieczenie emerytalne');
        $this->aTax['disability-insurance'] = new Tax(0, TaxValueType::Percentile, 'Ubezpieczenie rentowe');
        $this->aTax['accident-insurance'] = new Tax(0, TaxValueType::Percentile, 'Ubezpieczenie wypadkowe');
        $this->aTax['labor-fund'] = new Tax(0, TaxValueType::Percentile, 'Fundusz Pracy'); // Fundusz Pracy
        $this->aTax['gebf'] = new Tax(0, TaxValueType::Percentile, 'FGŚP'); // Fundusz Gwarantowanych Świadczeń Pracowniczych

        //pracownik
        $this->aTax['pension-contribution'] = new Tax(9.76, TaxValueType::Percentile, 'Składka emerytalna');
        $this->aTax['disability-contribution'] = new Tax(1.5, TaxValueType::Percentile, 'Składka rentowa');
        $this->aTax['sickness-contribution'] = new Tax(2.45, TaxValueType::Percentile, 'Składka chorobowa');
        $this->aTax['health-insurance'] = new Tax(9, TaxValueType::Percentile, 'Ubezpieczenie zdrowotne');
        $this->aTax['health-insurance-deduction'] = new Tax(7.75, TaxValueType::Percentile, 'Odliczenie od ubezpieczenia zdrowotnego');
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
        
        $incomeCost = new Tax(50, TaxValueType::Percentile, 'Koszt uzyskania przychodu');
        $incomeCost->cal($base);

        $advanceBase = round($base - $incomeCost->getOutcome());

        $this->aTax['income-tax']->cal($advanceBase);

        $advance = round($this->aTax['income-tax']->getOutcome() - $this->aTax['health-insurance-deduction']->getOutcome());
        $this->aTax['advance'] = new Tax(null, TaxValueType::Constant, 'Zaliczka do urzędu skarbowego', ($advance > 0 ? $advance : 0));

        $this->netSalary = $base - $this->aTax['advance']->getOutcome() - $this->aTax['health-insurance']->getOutcome();   
    }

    public function calculateGrossToCostOfEmployer($grossSalary) {
        
        $this->grossSalary = $grossSalary;
        $this->costOfEmployer = $grossSalary;
    }
}