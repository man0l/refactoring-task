<?php

namespace App;

use App\Bin\Enums;
use App\Bin\RequestInterface;
use App\Bin\TransformerInterface;

class Commission
{
    private const KEY_DATA_BIN = 'bin';
    private const KEY_DATA_COUNTRY = 'country';
    private const EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES',
        'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
        'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];
    private RequestInterface $request;
    private TransformerInterface $transform;

    protected string $country;

    public function __construct(RequestInterface $request, TransformerInterface $transform)
    {
        $this->request = $request;
        $this->transform = $transform;
    }

    public function calc($line)
    {
        $data = json_decode($line, true);
        if (!isset($data)) {
            return;
        }

        $this->country = $this->getCountry($data[self::KEY_DATA_BIN]);

        if ($this->isEUCountry()) {

        }
    }

    public function getCountry($bin): string
    {
        $content = $this->request->request($bin);
        $json = json_decode($content, true);
        $transformed = $this->transform->transform($json);
        return $transformed[Enums::KEY_COUNTRY];
    }

    protected function isEUCountry(): bool|string
    {
        if (empty($this->country)) {
            return false;
        }

        return in_array($this->country, self::EU_COUNTRIES);
    }


}