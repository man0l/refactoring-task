<?php 

namespace App\Bin;

class BinListRequest implements RequestInterface {
    protected string $url = "https://lookup.binlist.net/";
    public function request() {}
}