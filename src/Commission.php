<?php

namespace App;

use App\Bin\RequestInterface;
use App\Bin\TransformerInterface;

class Commission {
    protected RequestInterface $request;
    protected TransformerInterface $transform;
    
    public function __construct(RequestInterface $request, TransformerInterface $transform) {
        $this->request = $request;
        $this->transform = $transform;
    }
    
    function calc($line) {
        $data = json_decode($line, true);
        if (!isset($data)) {
            return;
        } 
        
        $this->request->request($data['bin']);
    }
}