<?php namespace IOProject\Accountancy;

use IOProject\Core\Common;
use IOProject\Accountancy\Enums\TaxValueType;

class Tax implements \JsonSerializable {
    
    private $name;
    private $value;
    private $valueType;
    private $outcome;

    public function __construct($name, $valueType, $value = 0) {
        $this->name = $name;
        $this->valueType = $valueType;
        $this->value = $value;
        $this->outcome = $this->valueType == TaxValueType::Constant ? $this->value : 0;
    }

    public function getName() {
        return $this->name;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function getValueType() {
        return $this->valueType;
    }

    public function setOutcome($outcome) {
        $this->outcome = $outcome;
    }

    public function getOutcome() {
        return $this->outcome;
    }

    public function cal($amount = 0) {
        if ($this->valueType == TaxValueType::Constant)
            return $this->outcome = $this->value;
        else if ($this->valueType == TaxValueType::Percentile)
            return $this->outcome = Common::calculatePercentage($amount, $this->value);
        return null;
    }

    public function jsonSerialize() {
        return [
            'outcome' => $this->outcome,
            'name' => $this->name
        ];
    }
}