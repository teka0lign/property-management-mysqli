<?php
namespace App\Middleware;

use App\Config;

final class AuthMiddleware
{
    public static function authenticate(): ?int
    {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        if (!$token) return null;
        // expecting `Bearer token`
        if (stripos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        if (hash_equals($token, Config::API_TOKEN)) {
            // demo user id
            return 1;
        }
        return null;
    }
}
