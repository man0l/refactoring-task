<?php

namespace App;

use App\Bin\Enums;

class TransactionData
{
    private string $filePath;
    private array $lines = [];

    public function __construct($filePath)
    {

        if (empty($filePath)) {
            throw new \Exception("Please pass the input file as an argument");
        }

        $this->filePath = $filePath;

    }

    public function read()
    {
        $fp = fopen($this->filePath, "r");
        if (!$fp) {
            throw new \Exception('The requested file could not be found!');
        }

        while (!feof($fp)) {
            $this->lines[] = fgets($fp);
        }

        fclose($fp);
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function getAllCurrencies()
    {
        return array_map(function ($item) {
            if ($item) {
                $data = json_decode($item, true);
                return $data[Enums::KEY_CURRENCY];
            }
        }, $this->lines);
    }
}