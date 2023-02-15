<?php

use App\Bin\Enums;
use PHPUnit\Framework\TestCase;
use App\Commission;
use App\Bin\BinListRequest;
use App\Bin\BinListTransformer;
use App\TransactionData;
use App\Rates\ExchangeRatesApiRequest;
use App\Rates\ExchangeRatesApiTransformer;

class TestCommission extends TestCase
{
    /**
     * @dataProvider provideTransactions
     */
    public function testCommission($line, $currencies, $binListResult, $tax)
    {

        $data = json_decode($line, true);
        $binListData = json_decode($binListResult, true);
        $currencyData = json_decode('{"base": "EUR",  "date": "2023-02-15", "rates": { "EUR": 1, "GBP": 0.888232, "JPY": 143.287409, "USD": 1.0676 }, "success": true, "timestamp": 1676486883}', true);
        $binRequest = Mockery::mock(BinListRequest::class);
        $binRequest->shouldReceive('request')->with($data[Commission::KEY_DATA_BIN])->andReturn($binListResult);
        $binTransformer = Mockery::mock(BinListTransformer::class);
        $binTransformer->shouldReceive('transform')->andReturn([Enums::KEY_COUNTRY => $binListData['country']['alpha2']]);
        $rateApi = Mockery::mock(ExchangeRatesApiRequest::class);
        $rateApi->shouldReceive('request')->andReturn('{"base": "EUR",  "date": "2023-02-15", "rates": { "EUR": 1, "GBP": 0.888232, "JPY": 143.287409, "USD": 1.0676 }, "success": true, "timestamp": 1676486883}');
        $rateTransformer = Mockery::mock(ExchangeRatesApiTransformer::class);
        $rateTransformer->shouldReceive('transform')->andReturn([
            Enums::KEY_RATE_CURRENCY_TO => $currencyData['rates'][$data[Enums::KEY_CURRENCY]]
        ]);
        $commission = new Commission($binRequest, $binTransformer, $rateApi, $rateTransformer);
        $calc = $commission->calc($line, $currencies);
        $this->assertEquals($calc, $tax);
    }

    public static function provideTransactions()
    {

        return [
            ['{"bin":"45717360","amount":"100.00","currency":"EUR"}', ['EUR', 'JPY', 'GBP', 'USD'], '{"number":{"length":16,"luhn":true},"scheme":"visa","type":"debit","brand":"Visa/Dankort","prepaid":false,"country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank","url":"www.jyskebank.dk","phone":"+4589893300","city":"Hjørring"}}', 1],
            ['{"bin":"516793","amount":"50.00","currency":"USD"}', ['EUR', 'JPY', 'GBP', 'USD'], '{"number":{},"scheme":"mastercard","type":"debit","brand":"Debit","country":{"numeric":"440","alpha2":"LT","name":"Lithuania","emoji":"🇱🇹","currency":"EUR","latitude":56,"longitude":24},"bank":{}}', 0.47],
            ['{"bin":"45417360","amount":"10000.00","currency":"JPY"}', ['EUR', 'JPY', 'GBP', 'USD'], '{"number":{"length":16,"luhn":true},"scheme":"visa","type":"credit","brand":"Traditional","prepaid":false,"country":{"numeric":"392","alpha2":"JP","name":"Japan","emoji":"🇯🇵","currency":"JPY","latitude":36,"longitude":138},"bank":{"name":"CREDIT SAISON CO., LTD.","url":"corporate.saisoncard.co.jp","phone":"(03)3988-2111"}}', 1.4],
        ];
    }


}