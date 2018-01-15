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
        // pracodawca
        $this->aTax['pension-insurance'] = new Tax('Ubezpieczenie emerytalne', TaxValueType::Percentile);
        $this->aTax['disability-insurance'] = new Tax('Ubezpieczenie rentowe', TaxValueType::Percentile);
        $this->aTax['accident-insurance'] = new Tax('Ubezpieczenie wypadkowe', TaxValueType::Percentile);
        $this->aTax['labor-fund'] = new Tax('Fundusz Pracy', TaxValueType::Percentile);
        $this->aTax['gebf'] = new Tax('FGŚP', TaxValueType::Percentile); // Fundusz Gwarantowanych Świadczeń Pracowniczych
        // pracownik
        $this->aTax['pension-contribution'] = new Tax('Składka emerytalna', TaxValueType::Percentile, 9.76);
        $this->aTax['disability-contribution'] = new Tax('Składka rentowa', TaxValueType::Percentile, 1.5);
        $this->aTax['sickness-contribution'] = new Tax('Składka chorobowa', TaxValueType::Percentile, 2.45);
        $this->aTax['health-insurance'] = new Tax('Ubezpieczenie zdrowotne', TaxValueType::Percentile, 9);
        $this->aTax['health-insurance-deduction'] = new Tax('Odliczenie od ubezpieczenia zdrowotnego', TaxValueType::Percentile, 7.75);

        $this->aTax['income-tax'] = new Tax('Podatek dochodowy', TaxValueType::Percentile, 18);
        $this->aTax['advance'] = new Tax('Zaliczka do urzędu skarbowego', TaxValueType::Constant); 
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