<?php namespace IOProject\Accountancy;

class Tax {
    
    private $name;
    private $type;
    private $value;

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getValue() {
        return $this->value;
    }
}