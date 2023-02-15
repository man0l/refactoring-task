<?php
$autoloader = require __DIR__ . '/vendor/autoload.php';

use App\Commission;
use App\Bin\BinListRequest;
use App\Bin\BinListTransformer;
use App\TransactionData;

$transactionData = new TransactionData($argv[1]);
$transactionData->read();
$lines = $transactionData->getLines();

$commission = new Commission(new BinListRequest(), new BinListTransformer());

foreach ($lines as $line) {    
    $commission->calc($line);    
}
