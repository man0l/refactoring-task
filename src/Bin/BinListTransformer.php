<?php

namespace App\Bin;

class BinListTransformer implements TransformerInterface
{
    private const KEY_CONTENT_COUNTRY = 'alpha2';
    private const KEY_CONTENT_COUNTRY_SECTION = 'country';

    public function transform(string $content): array
    {
        $json = json_decode($content, true);
        return [
            Enums::KEY_COUNTRY => $json[self::KEY_CONTENT_COUNTRY_SECTION][self::KEY_CONTENT_COUNTRY],
        ];
    }
}