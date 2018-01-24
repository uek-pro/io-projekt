<?php namespace IOProject\Accountancy\ContractSalary;

use IOProject\Core\Common;
use IOProject\Accountancy\Tax;
use IOProject\Accountancy\Enums\TaxValueType;

abstract class ContractTaxCalculation implements \JsonSerializable {

    protected $netSalary;
    protected $grossSalary;
    protected $costOfEmployer;
    protected $aTax = array();

    protected function __construct() {
        // pracodawca
        $this->aTax['pension-insurance'] = new Tax('Ubezpieczenie emerytalne', TaxValueType::PERCENTILE);
        $this->aTax['disability-insurance'] = new Tax('Ubezpieczenie rentowe', TaxValueType::PERCENTILE);
        $this->aTax['accident-insurance'] = new Tax('Ubezpieczenie wypadkowe', TaxValueType::PERCENTILE);
        $this->aTax['labor-fund'] = new Tax('Fundusz Pracy', TaxValueType::PERCENTILE);
        $this->aTax['gebf'] = new Tax('FGŚP', TaxValueType::PERCENTILE); // Fundusz Gwarantowanych Świadczeń Pracowniczych
        // pracownik
        $this->aTax['pension-contribution'] = new Tax('Składka emerytalna', TaxValueType::PERCENTILE, 9.76);
        $this->aTax['disability-contribution'] = new Tax('Składka rentowa', TaxValueType::PERCENTILE, 1.5);
        $this->aTax['sickness-contribution'] = new Tax('Składka chorobowa', TaxValueType::PERCENTILE, 2.45);
        $this->aTax['health-insurance'] = new Tax('Ubezpieczenie zdrowotne', TaxValueType::PERCENTILE, 9);
        $this->aTax['health-insurance-deduction'] = new Tax('Odliczenie od ubezpieczenia zdrowotnego', TaxValueType::PERCENTILE, 7.75);

        $this->aTax['income-tax'] = new Tax('Podatek dochodowy', TaxValueType::PERCENTILE, 18);
        $this->aTax['advance'] = new Tax('Zaliczka do urzędu skarbowego', TaxValueType::CONSTANT); 
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