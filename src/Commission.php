<?php

namespace App;

use App\Bin\Enums;
use App\Bin\RequestInterface;
use App\Bin\TransformerInterface;
use App\Rates\RatesRequestInterface;
use App\Rates\RatesTransformerInterface;

class Commission
{
    private const KEY_DATA_BIN = 'bin';
    private const KEY_DATA_AMOUNT = 'amount';
    private const KEY_DATA_CURRENCY = 'currency';

    private const MAIN_CURRENCY = 'EUR';
    private const EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES',
        'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
        'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];
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
        if (!isset($data)) {
            return;
        }

        $content = $this->request->request($data[self::KEY_DATA_BIN]);
        $transformed = $this->transform->transform($content);

        $this->country = $transformed[Enums::KEY_COUNTRY];
        $ratesResponse = $this->rates->request($data[self::KEY_DATA_CURRENCY], $supportedCurencies);
        $ratesTransformed = $this->ratesTransformer->transform($ratesResponse, $data[self::KEY_DATA_CURRENCY]);

        if ($this->isEUCountry()) {

        }
    }


    protected function isEUCountry(): bool|string
    {
        if (empty($this->country)) {
            return false;
        }

        return in_array($this->country, self::EU_COUNTRIES);
    }


}