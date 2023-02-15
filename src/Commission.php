<?php

namespace App;

use App\Bin\Enums;
use App\Bin\RequestInterface;
use App\Bin\TransformerInterface;
use App\Rates\RatesRequestInterface;
use App\Rates\RatesTransformerInterface;

class Commission
{
    public const KEY_DATA_BIN = 'bin';
    private const KEY_DATA_AMOUNT = 'amount';
    private const KEY_DATA_CURRENCY = 'currency';

    private const MAIN_CURRENCY = 'EUR';

    private const EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES',
        'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
        'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];

    private const EU_TAX_RATE = 0.01;
    private const NON_EU_TAX_RATE = 0.02;
    private RequestInterface $request;
    private TransformerInterface $transform;

    private RatesRequestInterface $rates;
    private RatesTransformerInterface $ratesTransformer;

    protected string $country;

    public function __construct(RequestInterface $request, TransformerInterface $transform, RatesRequestInterface $rates, RatesTransformerInterface $ratesTransformer)
    {
        $this->request = $request;
        $this->transform = $transform;
        $this->rates = $rates;
        $this->ratesTransformer = $ratesTransformer;
    }

    public function calc(string $line, array $supportedCurencies = [])
    {
        $data = json_decode($line, true);
        var_dump($data);
        if (!isset($data)) {
            return null;
        }

        $content = $this->request->request($data[self::KEY_DATA_BIN]);
        $transformed = $this->transform->transform($content);

        $this->country = $transformed[Enums::KEY_COUNTRY];
        $rate = 1;
        if ($data[self::KEY_DATA_CURRENCY] != self::MAIN_CURRENCY) {
            $ratesResponse = $this->rates->request($data[self::KEY_DATA_CURRENCY], $supportedCurencies);
            $ratesTransformed = $this->ratesTransformer->transform($ratesResponse, $data[self::KEY_DATA_CURRENCY]);
            $rate = $ratesTransformed[Enums::KEY_RATE_CURRENCY_TO];
        }

        $amount = $data[Enums::KEY_AMOUNT] / $rate;

        if ($this->isEUCountry()) {
            return round($amount * self::EU_TAX_RATE, 2);
        }

        return round($amount * self::NON_EU_TAX_RATE, 2);
    }


    protected function isEUCountry(): bool|string
    {
        if (empty($this->country)) {
            return false;
        }

        return in_array($this->country, self::EU_COUNTRIES);
    }


}