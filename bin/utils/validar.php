<?php

namespace utils;

use DateTime;

trait validar
{
    public function validarFecha($date, $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        $errors = DateTime::getLastErrors();
        if ($errors) {
            return false;
        }
        return $d && $d->format($format) == $date;
    }

    public function http_error($code, $error): array
    {
        http_response_code($code);
        return ['resultado' => 'error', 'msg' => $error];
    }
    public function validarString($tipo, $input): int|bool
    {
        $regex = [
            "nombre" => "/^[a-zA-ZÀ-ÿ ]{3,30}$/",
            "razon_social" => "/^[a-zA-ZÀ-ÿ ]{7,200}$/",
            "contraseña"  => "/^[A-Za-z0-9 *?=&_!¡()@#]{8,30}$/",
            "correo" => "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
            "direccion" => "/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){7,200}$/",
            "cedula" => "/^[0-9]{7,10}$/",
            "rif" => "/^J-[0-9]{9,10}$/",
            "documento" => "/^[VEJ]-[A-Z0-9]{7,12}$/",
            "fecha" => "/^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/",
            "fecha_es" => "/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/",
            "datetime" => "/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/",
            "numero" => "/^[0-9]+$/",
            "decimal" => "/^([0-9]+\.+[0-9]|[0-9])+$/",
            "entero" => "/^[1-9]\d*$/",
            "string" => "/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9#\/\s,.-]){3,50}$/",
            "long_string" => "/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s(),.-]){1,5000}$/",
            "cuenta_bancaria" => "/^(?=.*[0-9])(?=.*[-])[0-9-]{1,25}$/",
            "factura" => "/^N°-[A-Za-z0-9]{6,15}$/u"
        ];
        if (!isset($regex[$tipo])) {
            $res = $this->http_error(500, "No existe el tipo de dato en las expresiones regulares almacenadas.");
            die(json_encode($res));
        }

        return preg_match($regex[$tipo], $input);
    }

    public function validarEstructuraArray($array, $estructura, $subarray = false): bool
    {
        if (!is_array($array)) {
            return false;
        }
        if (!$subarray) {
            foreach ($estructura as $clave => $tipo) {
                if (!array_key_exists($clave, $array) || gettype($array[$clave]) !== $tipo) {
                    return false;
                }
            }
        } else {
            foreach ($array as $subarray) {
                foreach ($estructura as $clave => $tipo) {
                    if (!array_key_exists($clave, $subarray) || gettype($subarray[$clave]) !== $tipo) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
    public function validarImagen($img, $array = false): array|bool
    {
        if (!$array) {
            if ($img['error'] > 0) {
                return $this->http_error(400, 'Error de imágen');
            }
            if ($img['type'] != 'image/jpeg' && $img['type'] != 'image/jpg' && $img['type'] != 'image/png') {
                return $this->http_error(400, 'Tipo de imagen inválido.');
            }
        }
        if ($array) {
            for ($i = 0; $i < count($img['name']); $i++) {
                if ($img['error'][$i] > 0) {
                    return ['valid' => false, 'res' => fn () => $this->http_error(400, 'Error de imágen')];
                }
                if ($img['type'][$i] != 'image/jpeg' && $img['type'][$i] != 'image/jpg' && $img['type'][$i] != 'image/png') {
                    return ['valid' => false, 'res' => fn () => $this->http_error(400, 'Tipo de imagen inválido.')];
                }
            }
        }
        return ['valid' => true];
    }
    public function randomRepository($repo, $filename, $identifier = "")
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $date = date('m/d/Yh:i:sa', time());
        $rand = rand(1000, 9999);
        $imgName = $date . $rand;
        $nameEnc = md5($imgName);
        return $repo . $identifier . $nameEnc . '.' . $extension;
    }
}
