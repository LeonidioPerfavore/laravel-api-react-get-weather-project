<?php

namespace App\Services;

use Firebase\JWT\JWT;

class TokenService
{
    public static function generateToken($payload): string
    {
        $encryption_algorithm = 'HS256';

        return JWT::encode($payload, $_ENV['JWT_SECRET_KEY'], $encryption_algorithm);
    }
}