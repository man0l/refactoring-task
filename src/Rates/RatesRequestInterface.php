<?php

namespace App\Rates;

interface RatesRequestInterface
{
    public function request(string $to, array $currencies = []);
}