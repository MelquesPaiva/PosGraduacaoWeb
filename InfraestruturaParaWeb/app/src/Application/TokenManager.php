<?php

namespace Infraweb\App\Application;

class TokenManager
{

    public static function generateTokenForData(array $tokenData): string
    {
        return base64_encode(json_encode($tokenData));
    }

    public static function getDataFromToken(string $token): array|\stdClass|null
    {
        $data = json_decode(base64_decode($token), true);
        if (!$data) {
            return null;
        }
        return $data;
    }
}
