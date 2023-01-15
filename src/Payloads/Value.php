<?php

namespace FilippoToso\Euribor\Payloads;

class Value
{
    public $date;
    public $value;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function make($data = [])
    {
        return new static($data);
    }
}
