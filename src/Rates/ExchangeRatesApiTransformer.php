<?php

namespace App\Rates;

use App\Bin\Enums;

class ExchangeRatesApiTransformer implements RatesTransformerInterface
{
    public const KEY_RATES = 'rates';


    public function transform(string $content, string $toCurrency)
    {
        $data = json_decode($content, true);
        return [
            Enums::KEY_RATE_CURRENCY_TO => $data[self::KEY_RATES][$toCurrency],
        ];
    }
}