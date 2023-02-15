<?php

namespace App\Rates;

class ExchangeRatesApiRequest implements RatesRequestInterface
{
    const API_KEY = 'CNp7KVPdu79Lk31Z6homD9olauSlzjNn';
    protected string $url = "https://api.apilayer.com/exchangerates_data/latest";
    private string $cached = "";

    public function request(string $to, array $currencies = [])
    {

        if (!empty($this->cached)) {
            return $this->cached;
        }
        
        $urlPath = sprintf("?symbols=%s&base=%s", rawurlencode(implode(",", $currencies)), $to);
        $context = stream_context_create([
            'http' => [
                'header' => "Content-Type: text/plain\r\napikey: " . self::API_KEY
            ]
        ]);

        $converted = file_get_contents($this->url . $urlPath, true, $context);
        $this->cached = $converted;
        return $converted;
    }
}