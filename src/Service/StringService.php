<?php

namespace App\Service;

class StringService
{
    public function getToken(int $length = 32): string
    {
        $token = bin2hex(random_bytes($length / 2));
        return $token;
    }

    public function getSlug(string $string): string
    {
        $string = \transliterator_transliterate("Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();", $string);
        $string = preg_replace('/[-\s]+/', '-', $string);
        return trim($string, '-');
    }
}