<?php
$autoloader = require __DIR__ . '/vendor/autoload.php';

use App\Commission;
use App\Bin\BinListRequest;
use App\Bin\BinListTransformer;
use App\TransactionData;
use App\Rates\ExchangeRatesApiRequest;
use App\Rates\ExchangeRatesApiTransformer;

$transactionData = new TransactionData($argv[1]);
$transactionData->read();
$allCurrencies = $transactionData->getAllCurrencies();
$lines = $transactionData->getLines();

$commission = new Commission(
    new BinListRequest(), new BinListTransformer(),
    new ExchangeRatesApiRequest(), new ExchangeRatesApiTransformer()
);

foreach ($lines as $line) {
    echo $commission->calc($line, $allCurrencies), "\n";

}
