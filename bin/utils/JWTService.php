<?php

namespace utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use utils\validar;

class JWTService
{
    use validar;
    private static $secret_key;
    private static $encrypt = 'HS256';
    private static $exp = ['1h' => 3600];

    public static function init()
    {
        self::$secret_key = $_ENV['SECRET_KEY'];
    }
    private static function http_error($code, $msg): array
    {
        $validar = new class()
        {
            use validar;
        };
        return $validar->http_error($code, $msg);
    }

    public static function generateToken($data)
    {
        try {
            $time = time();
            $token = [
                'iat' => $time, // Tiempo en que fue generado el token
                'exp' => $time + self::$exp['1h'], // Tiempo en el que expirará el token (1 hora)
                'data' => $data
            ];

            return JWT::encode($token, self::$secret_key, self::$encrypt);
        } catch (\Throwable $th) {
            die(json_encode(self::http_error(403, "Token inválido.")));
        }
    }

    public static function validateToken($token)
    {
        if (empty($token)) {
            die(json_encode(self::http_error(403, "Token inválido.")));
        }

        try {
            $decoded = JWT::decode($token, new Key(self::$secret_key, self::$encrypt));
            return (object) $decoded->data;
        } catch (\Exception $e) {
            die(json_encode(self::http_error(403, "Token inválido.")));
        }
    }
    public static function validateSession(): bool | object
    {
        $headers = apache_request_headers();
        if (!isset($headers['authorization'])) {
            return false;
        }
        $token = explode(' ', $headers['authorization']);
        if (!isset($token[1])) {
            return false;
        }
        return self::validateToken($token[1]);
    }
}
JWTService::init();
