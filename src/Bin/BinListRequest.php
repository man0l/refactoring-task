<?php 

namespace App\Bin;

class BinListRequest implements RequestInterface {
    protected string $url = "https://lookup.binlist.net/";
    public function request($bin) {
        try {            
            $content = file_get_contents($this->url . $bin);
        } catch (Exception $e) {
            
        }
    }
}