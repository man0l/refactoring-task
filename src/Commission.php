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
    
    function calc() {}
}