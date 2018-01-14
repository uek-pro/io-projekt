<?php namespace IOProject\Accountancy;

use IOProject\Core\Common;
use IOProject\Accountancy\Enums\TaxValueType;

class Tax implements \JsonSerializable {
    
    private $name;
    private $value;
    private $valueType;
    private $outcome;

    // public function __construct($name, $valueType) {
    //     $this->name = $name;
    //     $this->valueType = $valueType;
    // }

    public function __construct($value, $valueType, $name, $outcome = 0) {
        $this->value = $value;
        $this->valueType = $valueType;
        $this->name = $name;
        $this->outcome = $outcome;
    }

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function getValueType() {
        return $this->valueType;
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