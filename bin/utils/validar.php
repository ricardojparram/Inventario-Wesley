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
}
