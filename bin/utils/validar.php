<?php

namespace utils;

use DateTime;

trait validar {
    public function validarFecha($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    public function http_error($code, $error) {
        http_response_code($code);
        return ['resultado' => 'error', 'msg' => $error];
    }
    public function validarString($tipo, $input) {
        $regex = [
            "nombre" => "/^[a-zA-ZÀ-ÿ ]{1,30}$/",
            "contraseña"  => "/^[A-Za-z0-9 *?=&_!¡()@#]{8,30}$/",
            "correo" => "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
            "direccion" => "/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s#\/,.-]){7,160}$/",
            "cedula" => "/^[0-9]{7,10}$/",
            "rif" => "/^J-[0-9]{9,10}$/",
            "documento" => "/^[VEJ]-[A-Z0-9]{7,12}$/",
            "fecha" => "/^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/",
            "fecha_es" => "/^([0-9]{2}\/[0-9]{2}\/[0-9]{4})$/",
            "datetime" => "/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/",
            "decimal" => "/^([0-9]+\.+[0-9]|[0-9])+$/",
            "entero" => "/^[1-9]\d*$/",
            "string" => "/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9#\/s,.-]){3,50}$/",
            "long_string" => "/^[a-zA-ZÀ-ÿ]+([a-zA-ZÀ-ÿ0-9\s(),.-]){1,5000}$/",
            "cuenta_bancaria" => "/^(?=.*[0-9])(?=.*[-])[0-9-]{1,25}$/",
            "factura" => "/^N°-[A-Za-z0-9]{6,15}$/u"
        ];
        if (!isset($regex[$tipo]))
            die("No existe el tipo de dato en las expresiones regulares almacenadas.");

        return preg_match($regex[$tipo], $input);
    }

    public function validarEstructuraArray($array, $estructura, $subarray = false) {
        if (!is_array($array)) return false;
        if (!$subarray) {
            foreach ($estructura as $clave => $tipo) {
                if (!array_key_exists($clave, $array) || gettype($array[$clave]) !== $tipo)
                    return false;
            }
        } else {
            foreach ($array as $subarray) {
                foreach ($estructura as $clave => $tipo) {
                    if (!array_key_exists($clave, $subarray) || gettype($subarray[$clave]) !== $tipo)
                        return false;
                }
            }
        }

        return true;
    }
}
