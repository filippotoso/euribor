<?php

namespace FilippoToso\Euribor;

use FilippoToso\Api\Sdk\Sdk;
use FilippoToso\Api\Sdk\Support\Options;
use FilippoToso\Euribor\Endpoints\Values;

class Euribor extends Sdk
{
    protected const URL = 'https://www.euribor-rates.eu/en/current-euribor-rates';

    public const EURIBOR_1_WEEK = 'euribor-1-week';
    public const EURIBOR_1_MONTH = 'euribor-1-month';
    public const EURIBOR_3_MONTHS = 'euribor-3-months';
    public const EURIBOR_6_MONTHS = 'euribor-6-months';
    public const EURIBOR_12_MONTHS = 'euribor-12-months';

    public function __construct()
    {
        $options = new Options([
            'uri' => static::URL,
        ]);

        parent::__construct($options);
    }

    public function values(): Values
    {
        return new Values($this);
    }

    public static function read()
    {
        return (new static)->values()->read();
    }
}
