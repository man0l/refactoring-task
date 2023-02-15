<?php
$autoloader = require __DIR__ . '/vendor/autoload.php';

use App\Commission;
use App\Bin\BinListRequest;
use App\Bin\BinListTransformer;

$commission = new Commission(new BinListRequest(), new BinListTransformer());
$commission->calc();