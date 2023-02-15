<?php

namespace App\Rates;

interface RatesTransformerInterface
{
    public function transform(string $content, string $toCurrency);
}