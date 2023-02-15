<?php

namespace App\Bin;

interface TransformerInterface
{
    public function transform(string $content);
}