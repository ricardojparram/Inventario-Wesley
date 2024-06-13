<?php

namespace utils;

use Exception;
use utils\validar;

class CryptoService
{
    use validar;
    private $private_key;
    private $public_key;
    public function __construct()
    {
        $this->private_key = $_ENV['PRIVATE_KEY'];
        $this->public_key = $_ENV['PUBLIC_KEY'];
    }

    public function getPublicKey()
    {
        if (!$this->public_key) {
            return $this->http_error(500, "Error al cargar la clave pública.");
        }
        return $this->public_key;
    }
    public function getPrivateKey()
    {
        if (!$this->private_key) {
            return $this->http_error(500, "Error al cargar la clave privada.");
        }
        return $this->private_key;
    }
    public function decrypt($data)
    {
        try {

            $private_key = openssl_pkey_get_private($this->private_key);
            if (!$private_key) {
                throw new Exception('Error al cargar la clave privada.');
            }
            $msg = base64_decode($data);
            if (!openssl_private_decrypt($msg, $decryptedData, $private_key)) {
                throw new Exception('Error al desencriptar los datos: ' . openssl_error_string());
            }
            return ['resultado' => 'ok', 'msg' => $decryptedData];
        } catch (\Exception $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
    public function encrypt($data)
    {
        try {

            $publicKey = openssl_pkey_get_public($this->public_key);
            if (!$publicKey) {
                throw new Exception('Error al cargar la clave pública.');
            }
            if (!openssl_public_encrypt($data, $encryptedData, $publicKey)) {
                throw new Exception('Error al cifrar los datos: ' . openssl_error_string());
            }
            return ['resultado' => 'ok', 'msg' => base64_encode($encryptedData)];
        } catch (\Exception $e) {
            return $this->http_error(500, $e->getMessage());
        }
    }
}
