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
    private static $exp = ['1h' => 3600, '24h' => 86400];

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
    private static function getToken()
    {
        $headers = apache_request_headers();

        $auth = match (true) {
            isset($headers['Authorization']) => $headers['Authorization'],
            isset($headers['authorization']) => $headers['authorization'],
            default => false
        };
        if (!$auth) return false;

        $token = explode(' ', $auth);

        return isset($token[1]) ? $token[1] : false;
    }
    public static function generateToken($data)
    {
        try {
            $time = time();
            $payload = [
                'iat' => $time, // Tiempo en que fue generado el token
                'exp' => $time + self::$exp['24h'], // Tiempo en el que expirará el token (1 hora)
                'data' => $data
            ];

            return JWT::encode($payload, self::$secret_key, self::$encrypt);
        } catch (\Throwable $th) {
            die(json_encode(self::http_error(403, "Token inválido.")));
        }
    }

    public static function validateToken($token, $returnType = false)
    {
        if (empty($token)) {
            die(json_encode(self::http_error(403, "Token inválido.")));
        }

        try {
            $decoded = JWT::decode($token, new Key(self::$secret_key, self::$encrypt));
            if ($returnType) {
                return ['resultado' => 'ok', 'msg' => 'Token valido', 'valid' => true];
            }
            return (array) $decoded->data;
        } catch (\Exception $e) {
            die(json_encode(self::http_error(403, "Token inválido.")));
        }
    }
    public static function validateSession($returnType = false): bool | array
    {
        $token = self::getToken();
        if (!$token) {
            return false;
        }
        return self::validateToken($token, $returnType);
    }
    public static function updateToken($data): string | array
    {
        $token = self::getToken();
        if (empty($token)) {
            return self::http_error(403, "Token no existe.");
        }

        try {
            $decoded = (array) JWT::decode($token, new Key(self::$secret_key, self::$encrypt),);

            $newData = (array) $decoded['data'];
            foreach ($data as $key => $value) {
                $newData[$key] = $value;
            }
            $decoded['data'] = $newData;
            return JWT::encode($decoded, self::$secret_key, self::$encrypt);
        } catch (\Exception $e) {
            die(json_encode(self::http_error(403, "Token ekisde.")));
        }
    }
}
JWTService::init();
