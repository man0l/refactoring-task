<?php
namespace App;
class TransactionData {
    private string $filePath;
    private $lines = [];

    public function __construct($filePath) {

        if (empty($filePath)) {
            throw new Exception("Please pass the input file as an argument");
        }

        $this->filePath = $filePath;

    }

    public function read() {
        $fp = fopen($this->filePath, "r");
        if (!$fp) {
            throw new Exception('The requested file could not be found!');
        }

        while(!feof($fp)) {
            $this->lines[] = fgets($fp);
        }
    }

    public function getLines() {
        return $this->lines;
    }
}