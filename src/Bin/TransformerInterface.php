<?php

namespace App\Bin;

interface TransformerInterface
{
    public function transform(array $json);
}